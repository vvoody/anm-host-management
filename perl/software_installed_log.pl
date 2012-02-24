#!/usr/bin/env perl

require "common.pl";

my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;


use Data::Dumper;
foreach $host (@$hosts_ref) {
    my ($host_id, $ip, $community) = @$host;
    $host_id = int($host_id);
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip);
    &MYLOG($0, "connect_snmp", "$ip", $err_snmp) if $err_snmp;
    next if !defined $snmp_sess;

    my ($hash_ref, $err_idxs) = snmp_get_cols($snmp_sess, [$hrSWInstalledIndex]);
    &MYLOG($0, "snmp_get_cols", "hrSWInstalledIndex", $err_idxs) if $err_idxs;
    next if !defined $hash_ref;
    @swinst_idxs = values %$hash_ref;

    my ($hash_ref, $err_cols) = snmp_get_cols($snmp_sess, [$hrSWInstalledName,
                                                           $hrSWInstalledType,
                                                           $hrSWInstalledDate]);
    &MYLOG($0, "snmp_get_cols", "swintname, swintcpu, swintmem", $err_cols) if $err_cols;
    next if !defined $hash_ref;

    @rows;
    foreach $idx (@swinst_idxs) {
        my ($name, $type, $last_update) = ($hash_ref->{$hrSWInstalledName . ".$idx"},
                                           $hash_ref->{$hrSWInstalledType . ".$idx"},
                                           $hash_ref->{$hrSWInstalledDate . ".$idx"});
        $field_values =  {"name" => $name,
                          "type" => $type,
                          "last_update" => $last_update,
                          "host_id" => $host_id};
            my $st = insert_hash($dbh, "software_installed_log", $field_values);
            &MYLOG($0, "insert_hash", "swinst|$fields_values", "insert failed") if !defined $st;
    }

#    print Dumper(@rows);
    print "\n============================\n";

    $snmp_sess->close();
    print "\n";
}
