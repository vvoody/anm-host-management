require "setting.pl";
require "oids.pl";

use DBI;
use DBD::mysql;
use Net::SNMP;
use Net::Ping;

sub MYLOG {
    my ($script, $func, $params, $err_msg) = @_;
    open (STDERR, "| tee -ai $LOGFILE");    # hmmm, not portable...
    print STDERR scalar localtime . " - $script, $func, $params: $err_msg.\n";
    close (STDERR);
}


sub OK {
    print "OK!\n";
}


sub FAILED {
    print "FAILED!\n";
}


# return a list (database_handle_object, err_msg),
# object will be set as undef if failed, caller must handle this.
sub connect_db {
    eval {
        $dbh = DBI->connect("DBI:mysql:database=$DATABASE;host=$HOST",
                             "$DBUSER", "$DBPASSWD", {'RaiseError' => 1});
    };
    if ($@) {
        return (undef, $@);   # program must handle this, maybe exit
    }
    return ($dbh, undef);
}


# return a list (list_of_rows_ref, err_msg),
# list_of_rows_ref will be set as undef if failed, caller must handle this.
sub get_rows {
    my $dbh = $_[0];    # DBI->connect()
    my $q = $_[1];      # "SELECT ... FROM ..."
    my $sth = $dbh->prepare($q);
    eval {
        $rows = $dbh->selectall_arrayref($sth);  # [[1, 'xx', 'zzz'], [4, 'aa', 'bbb']]
    };
    $sth->finish();
    if ($@) {
        return (undef, $@);
    }
#    print Dumper($rows);
    return ($rows, undef);
}


# return (Net::SNMP_object, err_msg),
# object will be set as undef if failed, caller must handle this.
sub connect_snmp {
    my $host = $_[0];
    my $community = $_[1] || "public";
    my ($session, $error) = Net::SNMP->session(
        -hostname    => $host,
        -version     => "snmpv2c",
        -timeout     => 5,
        -translate   => 0,
        -community   => $community,
        );
    $p = Net::Ping->new();  # 5 seconds
    $session = undef if ! $p->ping($host);
    $p->close();
    return ($session, $error);
}


# return ([1, 'localhost', 'public'], [2, '192.168.1.1', 'public'])
sub select_table_cols {
    my ($dbh, $tabname, $cols, $host_id) = @_;
    my $sql = sprintf("SELECT %s FROM %s WHERE host_id = $host_id and available = 1",
                      join(",", @$cols), $tabname);
    my ($l_ref, $err) = get_rows($dbh, $sql);
    return ($l_ref, $err);
}


# return (list_of_hosts_meta, err_msg)
sub get_hosts {
    my $dbh = $_[0];
    my ($l_ref, $err) = get_rows($dbh, "SELECT id, ip_name, community from hosts");
    return ($l_ref, $err);
}


# return { '1.2.3.4.0' => 'blabla', '1.2.3.8.0' => 11223344}
sub snmp_get_cols {
    my ($snmp_sess, $cols_ref) = @_;    # $cols_ref is a ref to list
    my $res = $snmp_sess->get_entries(-columns => $cols_ref);
    my $hash_ref = $snmp_sess->var_bind_list();
    if ($hash_ref) {
        return ($hash_ref, undef);
    }
    return (undef, $snmp_sess->error());
}


# insert {'host_id' => 1, 'errors' => 10, 'status' => 'running'} into database
sub insert_hash {
    my ($dbh, $table, $field_values) = @_;
    # sort to keep field order, and thus sql, stable for prepare_cached
    my @fields = sort keys %$field_values;
    my @values = @{$field_values}{@fields};
    my $sql = sprintf "insert into %s (%s) values (%s)",
        $table, join(",", @fields), join(",", ("?")x@fields);
    my $sth = $dbh->prepare_cached($sql);
    return $sth->execute(@values);
}


sub create_rrd_file {
    my ($rrdfile, $dsname) = @_;
    system('mkdir -p $(' . "dirname $rrdfile)");
    my $rc = system("/usr/bin/rrdtool",
                    "create",
                    $rrdfile,
                    "-s 300",
                    "DS:$dsname:GAUGE:600:U:U",
                    "RRA:AVERAGE:0.5:1:288",     # 1 day - step: 5 mins, rows: 288
                    "RRA:AVERAGE:0.5:6:336",     # 1 week - step: 30 mins, rows: 336
                    "RRA:AVERAGE:0.5:24:372",    # 1 month - step: 2 hours, rows: 372
                    "RRA:AVERAGE:0.5:144:730",   # 1 year - step: 12 hours, rows: 730
                    "RRA:MIN:0.5:1:288",
                    "RRA:MIN:0.5:6:336",
                    "RRA:MIN:0.5:24:372",
                    "RRA:MIN:0.5:144:730",
                    "RRA:MAX:0.5:1:288",
                    "RRA:MAX:0.5:6:336",
                    "RRA:MAX:0.5:24:372",
                    "RRA:MAX:0.5:144:730",
                    "RRA:LAST:0.5:1:288"
        );
    &MYLOG($0, "create_rrd_file", $rrdfile, "created($dsname)");
}


sub update_rrd_file {
    my ($fn, $dsname, $value) = @_;
    my $rrdfile = $RRD_DIR . $fn;
    if (! -e $rrdfile) {
        create_rrd_file($rrdfile, $dsname);
    }
    $value = $value || "U";    # unknown data
    my $rc = system("/usr/bin/rrdtool",
                    "update",
                    $rrdfile,
                    "-t" . $dsname,
                    "N:$value");
    print "$dsname: $value\n";
}


# similar to python's if __name__ == "__main__"
unless (caller) {
    print "Testing database connection... ";
    my ($dbh, $err_db) = connect_db();
    (&FAILED && die $err_db) if $err_db;
    &OK;

    print "Testing get_rows...\n";
    my ($rows_ref, $err_rows) = get_rows($dbh, "SELECT id, ip_name, community FROM hosts");
    (&FAILED && die $err_rows) if $err_rows;
    use Data::Dumper;
    print Dumper(@$rows_ref);
    $dbh->disconnect();
    &OK;

    print "Testing SNMP connect... ";
    my ($snmp_sess, $err_snmp) = connect_snmp("localhost");
    (&FAILED && die $err_snmp) if $err_snmp;
    $snmp_sess->close();
    &OK;
}
