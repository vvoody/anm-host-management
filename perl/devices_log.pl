#!/usr/bin/env perl

require "common.pl";


$hrDeviceTable        = "1.3.6.1.2.1.25.3.2";        # baseoid
$hrDeviceIndex        = "1.3.6.1.2.1.25.3.2.1.1";
$hrDeviceType         = "1.3.6.1.2.1.25.3.2.1.2";
$hrDeviceDescr        = "1.3.6.1.2.1.25.3.2.1.3";
$hrDeviceStatus       = "1.3.6.1.2.1.25.3.2.1.5";    # not every entry has this
$hrDeviceErrors       = "1.3.6.1.2.1.25.3.2.1.6";    # same as above


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;


# get devices metas from database
sub get_devices {
    my ($host_id) = @_;
    return select_table_cols($dbh, "devices", ["id", "device_idx"], $host_id);
}


use Data::Dumper;
foreach $host (@$hosts_ref) {
    my ($host_id, $ip, $community) = @$host;
    $host_id = int($host_id);
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip, $community);
    &MYLOG($0, "connect_snmp", "$ip", $err_snmp) if $err_snmp;
    next if !defined $snmp_sess;

    # $res = $snmp_sess->get_table(-baseoid => $hrDeviceTable);
    # my ($list_ref, $err_idxs) = snmp_get_cols($snmp_sess, $hrDeviceIndex);
    # &MYLOG($0, "snmp_get_cols", "$hrDeviceIndex", $err_idxs) if $err_idxs;
    # next if !defined $list_ref;
    # @devices_idx = @$list_ref;

    my ($devices_ref, $err_devices) = get_devices($host_id);
    &MYLOG($0, "get_devices", "", $err_devices) if $err_devices;
    next if !defined $devices_ref;

    foreach $device (@$devices_ref) {
        my ($id, $idx) = @$device;
        print "on device $idx of $id\n";
        my ($hrDeviceErrors) = ($hrDeviceErrors . ".$idx");
        my $res = $snmp_sess->get_request(-varbindlist => [$hrDeviceErrors]);
        &MYLOG($0, "snmp get_request", "[$hrDeviceErrors]", $snmp_sess->error()) if !defined $res;
        next if !defined $res;
#        print Dumper($res);
        my $field_values = {
            "device_id" => $id,
            "num_errors" => $res->{$hrDeviceErrors} || undef,
        };
        my $st = insert_hash($dbh, "devices_log", $field_values);
        &MYLOG($0, "insert_hash", "devices_log|$fields", "insert failed") if !defined $st;
    }

    $snmp_sess->close();
    print "\n";
}
