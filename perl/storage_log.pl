#!/usr/bin/env perl

require "common.pl";

$hrStorageTable              = "1.3.6.1.2.1.25.2.3";
$hrStorageIndex              = "1.3.6.1.2.1.25.2.3.1.1";
$hrStorageType               = "1.3.6.1.2.1.25.2.3.1.2";
$hrStorageDescr              = "1.3.6.1.2.1.25.2.3.1.3";
$hrStorageAllocationUnits    = "1.3.6.1.2.1.25.2.3.1.4";
$hrStorageSize               = "1.3.6.1.2.1.25.2.3.1.5";
$hrStorageUsed               = "1.3.6.1.2.1.25.2.3.1.6";
$hrStorageAllocationFailures = "1.3.6.1.2.1.25.2.3.1.7";    # can get, but is hrDeviceIndex


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;


# get storage metas from database
sub get_storage {
    my ($host_id) = @_;
    return select_table_cols($dbh, "storage", ["id", "storage_idx"], $host_id);
}


use Data::Dumper;
foreach $host (@$hosts_ref) {
    my ($host_id, $ip, $community) = @$host;
    $host_id = int($host_id);
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip, $community);
    &MYLOG($0, "connect_snmp", "$ip", $err_snmp) if $err_snmp;
    next if !defined $snmp_sess;

    my ($storage_ref, $err_storage) = get_storage($host_id);
    &MYLOG($0, "get_storage", "", $err_storage) if $err_storage;
    next if !defined $storage_ref;

    foreach $storage (@$storage_ref) {
        my ($id, $idx) = @$storage;
        print "on storage $idx of $id\n";
        my @oids = (
            $hrStorageUsed . ".$idx",
            $hrStorageAllocationFailures . ".$idx");
        my $res = $snmp_sess->get_request(-varbindlist => \@oids);
        &MYLOG($0, "snmp get_request", "@oids", $snmp_sess->error()) if !defined $res;
        next if !defined $res;

        my $field_values = {
            "storage_id" => $id,
            "used_capacity" => $res->{$oids[0]} || undef,
            "allocation_failures" => $res->{$oids[1]} || undef,
        };
        my $st = insert_hash($dbh, "storage_log", $field_values);
        &MYLOG($0, "insert_hash", "storage_log|$fields", "insert failed") if !defined $st;
    }

    $snmp_sess->close();
    print "\n";
}
