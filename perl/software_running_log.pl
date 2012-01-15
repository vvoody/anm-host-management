#!/usr/bin/env perl

require "common.pl";

$hrSWOSIndex          = "1.3.6.1.2.1.25.4.1.0";
$hrSWRunTable         = "1.3.6.1.2.1.25.4.2";
$hrSWRunEntry         = "1.3.6.1.2.1.25.4.2.1";
$hrSWRunIndex         = "1.3.6.1.2.1.25.4.2.1.1";    # pid
$hrSWRunName          = "1.3.6.1.2.1.25.4.2.1.2";
$hrSWRunType          = "1.3.6.1.2.1.25.4.2.1.6";
$hrSWRunStatus        = "1.3.6.1.2.1.25.4.2.1.7";

$hrSWRunPerf          = "1.3.6.1.2.1.25.5";
$hrSWRunPerfTable     = "1.3.6.1.2.1.25.5.1";
$hrSWRunPerfEntry     = "1.3.6.1.2.1.25.5.1.1";
$hrSWRunPerfCPU       = "1.3.6.1.2.1.25.5.1.1.1";
$hrSWRunPerfMem       = "1.3.6.1.2.1.25.5.1.1.2";


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;


# get software_running metas from database
sub get_swrun {
    my ($host_id) = @_;
    return select_table_cols($dbh, "software_running", ["id", "name"], $host_id);
}


use Data::Dumper;
foreach $host (@$hosts_ref) {
    my ($host_id, $ip, $community) = @$host;
    $host_id = int($host_id);
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip);
    &MYLOG($0, "connect_snmp", "$ip", $err_snmp) if $err_snmp;
    next if !defined $snmp_sess;

    # my ($swrun_ref, $err_swrun) = get_swrun($host_id);
    # &MYLOG($0, "get_swrun", "", $err_swrun) if $err_swrun;
    # next if !defined $swrun_ref;

    my ($hash_ref, $err_idxs) = snmp_get_cols($snmp_sess, [$hrSWRunIndex]);
    &MYLOG($0, "snmp_get_cols", "$hrSWRunIndex", $err_idxs) if $err_idxs;
    next if !defined $hash_ref;
    @swrun_idxs = values %$hash_ref;

    my ($hash_ref, $err_cols) = snmp_get_cols($snmp_sess, [$hrSWRunName,
                                                           $hrSWRunPerfCPU,
                                                           $hrSWRunPerfMem]);
    &MYLOG($0, "snmp_get_cols", "swrunname, swruncpu, swrunmem", $err_cols) if $err_cols;
    next if !defined $hash_ref;

    @rows;
    foreach $idx (@swrun_idxs) {
        my ($cpu_used, $mem_allocated, $name) = ($hash_ref->{$hrSWRunPerfCPU . ".$idx"},
                                                 $hash_ref->{$hrSWRunPerfMem . ".$idx"},
                                                 $hash_ref->{$hrSWRunName . ".$idx"});
        if ($cpu_used && $mem_allocated && $name) {
            $field_values =  {"cpu_used" => $cpu_used,
                              "mem_allocated" => $mem_allocated,
                              "name" => $name,
                              "host_id" => $host_id};
            my $st = insert_hash($dbh, "software_running_log", $field_values);
            &MYLOG($0, "insert_hash", "swrun_log|$fields_values", "insert failed") if !defined $st;
        }
    }

#    print Dumper(@rows);
    print "\n============================\n";

    $snmp_sess->close();
    print "\n";
}
