require "setting.pl";

use DBI;
use DBD::mysql;
use Net::SNMP;


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
    return ($session, $error);
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
