#!/usr/bin/env perl

# statistics.pl, insert/update/alarm {devices,storage,software_running}

require "common.pl";
require "oids.pl";
use lib "lib/";
use Set::Scalar;
use Data::Dumper;

# do statistics for devices
sub st_devices {
    my $host = $_[0];
    my ($host_id, $ip, $community) = @$host;
    $host_id = int($host_id);
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip);
    (&MYLOG($0, "connect_snmp", "$ip", $err_snmp) && return undef) if $err_snmp;

    my ($hash_ref, $err_idxs) = snmp_get_cols($snmp_sess, [$hrDeviceIndex]);
    &MYLOG($0, "snmp_get_cols", "$hrDeviceIndex", $err_idxs) if $err_idxs;
    return undef if !defined $hash_ref;
    my @new_idxs = values %$hash_ref;

    my ($l_ref, $err) = select_table_cols($dbh, "devices", ["id, device_idx"], $host_id);
    &MYLOG($0, "select_table_cols", "device_idx", $err) if $err;
    return undef if !defined $l_ref;

    my @old_idxs;
    my %devs;
    foreach $r (@$l_ref) {
        my @l = @$r;
        push @old_idxs, $l[1];
        $devs->{$l[1]} = $l[0];
    }

    my $s_old = Set::Scalar->new(@old_idxs);
    my $s_new = Set::Scalar->new(@new_idxs);

    my ($hash_ref, $err_cols) = snmp_get_cols($snmp_sess, [$hrDeviceDescr,
                                                           $hrDeviceType,
                                                           $hrDeviceStatus]);
    &MYLOG($0, "snmp_get_cols", "devdescr, devtype, devstatus", $err_cols) if $err_cols;
    return undef if !defined $hash_ref;

    # insert new, $s_new - $s_old;
    my $s_ins =  $s_new - $s_old;
    print "to be inserted devices: $s_ins\n";
    while (defined(my $e = $s_ins->each)) {
        my ($descr, $type, $status) = ($hash_ref->{$hrDeviceDescr . ".$e"},
                                       $hash_ref->{$hrDeviceType . ".$e"},
                                       $hash_ref->{$hrDeviceStatus . ".$e"});
        $field_values = {"descr"  => $descr || undef,
                         "type"   => $type || undef,
                         "status" => $status || undef,
                         "host_id" => $host_id,
                         "device_idx" => $e};
#        print Dumper($field_values);
        my $st = insert_hash($dbh, "devices", $field_values);
        &MYLOG($0, "insert_hash", "devices|$field_values", "insert failed") if !defined $st;
    }

    # update common if changed, $s_old * $s_new;
    # print "maybe need update: ";
    # my $s_update = $s_old * $s_new;
    # while (defined(my $e = $s_update->each)) {
    #     print $e . ", ";
    # }

    # alarm device disappeared, $s_old - $s_new;
    my $s_notavlb = $s_old - $s_new;
    print "alarm not available: $s_notavlb\n";
    while (defined(my $e = $s_notavlb->each)) {
#        $dbh->do("UPDATE devices SET available = 0 where host_id = $host_id and device_idx = $e");
        $field_values = {"tabname" => "devices",
                         "col" => "available",
                         "pre_value" => 1,
                         "now_value" => 0,
                         "level" => 2,
                         "tid" => $devs->{$e},
                         "comment" => "device $e of host $host_id is not available now!"};
        my $st = insert_hash($dbh, "statistics", $field_values);
        &MYLOG($0, "insert_hash", "statistics|$field_values", "insert failed") if !defined $st;
    }

    $snmp_sess->close();
}


# do statistics for storage
sub st_storage {
    ;
}


# do statistics for software_running
sub st_software_running {
    ;
}


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;

foreach $host (@$hosts_ref) {
    st_devices($host);
    # st_storage($host);
    # st_software_running($host);
    last;
}
