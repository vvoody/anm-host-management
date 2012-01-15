#!/usr/bin/env perl

require "common.pl";

$hrSystemUptime       = "1.3.6.1.2.1.25.1.1.0";    # hosts_log.uptime
$hrSystemNumUsers     = "1.3.6.1.2.1.25.1.5.0";    # hosts_log.num_users
$hrSystemMaxProcesses = "1.3.6.1.2.1.25.1.7.0";    # hosts_log.max_processes
$hrMemorySize         = "1.3.6.1.2.1.25.2.2.0";    # hosts_log.memsize
$hrSystemProcesses    = "1.3.6.1.2.1.25.1.6.0";    # hosts_log.num_loaded_processes

$TNAME = "hosts_log";
@TCOLS = ("host_id", "uptime", "num_users", "max_processes", "memsize", "num_loaded_processes", "status");


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;

#my(@hosts_list, $err_msg) = defined((@l = get_hosts_id())[0]) ? @l : die "$err_msg";

foreach $host (@$hosts_ref) {
    my ($host_id, $ip, $community) = @$host;
    $host_id = int($host_id);
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip);
    &MYLOG($0, "connect_snmp", "$ip", $err_snmp) if $err_snmp;
    next if !defined $snmp_sess;

    my $res = $snmp_sess->get_request(-varbindlist =>
                                      [$hrSystemUptime,
                                       $hrSystemNumUsers,
                                       $hrSystemMaxProcesses,
                                       $hrMemorySize,
                                       $hrSystemProcesses,
                                      ]);

    my $field_values = {
        "host_id" => $host_id,
        "uptime"  => $res->{$hrSystemUptime},
        "num_users" => $res->{$hrSystemNumUsers},
        "max_processes" => $res->{$hrSystemMaxProcesses},
        "memsize" => $res->{$hrMemorySize},
        "num_loaded_processes" => $res->{$hrSystemProcesses} || undef,
        "status" => 1,
    };

    insert_hash($dbh, $TNAME, $field_values);

    $snmp_sess->close();
    print "\n";
}

$dbh->disconnect();
