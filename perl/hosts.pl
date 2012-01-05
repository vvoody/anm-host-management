#!/usr/bin/env perl

#use strict;
require "common.pl";

# hosts_log.host_id
$hrSystemUptime       = "1.3.6.1.2.1.25.1.1.0";    # hosts_log.uptime
$hrSystemNumUsers     = "1.3.6.1.2.1.25.1.5.0";    # hosts_log.num_users
$hrSystemMaxProcesses = "1.3.6.1.2.1.25.1.7.0";    # hosts_log.max_processes
$hrMemorySize         = "1.3.6.1.2.1.25.2.2.0";    # hosts_log.memsize
$hrSystemProcesses    = "1.3.6.1.2.1.25.1.6.0";    # hosts_log.num_loaded_processes
# hosts_log.status


# return (list_of_hosts_meta, err_msg)
sub get_hosts {
    my $dbh = $_[0];
    my ($l_ref, $err) = get_rows($dbh, "SELECT id, ip_name, community from hosts");
    return ($l_ref, $err);
}


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;

#my(@hosts_list, $err_msg) = defined((@l = get_hosts_id())[0]) ? @l : die "$err_msg";

foreach $host (@$hosts_ref) {
    my ($host_id, $ip, $community) = @$host;
    print "Now on host $ip...\n";
    my ($snmp_sess, $err_snmp) = connect_snmp($ip);
    print STDERR $err_snmp . "\n" if $err_snmp;
    next if !defined $snmp_sess;

    my $res = $snmp_sess->get_request(-varbindlist =>
                                      [$hrSystemUptime,
                                       $hrSystemNumUsers,
                                       $hrSystemMaxProcesses,
                                       $hrMemorySize,
                                       $hrSystemProcesses,
                                      ]);

    # print "$res->{$hrSystemUptime} | $res->{$hrSystemNumUsers} | $res->{$hrSystemMaxProcesses} | $res->{$hrMemorySize} | ";
    # print $res->{$hrSystemProcesses} ? $res->{$hrSystemProcesses}: "undef" ;
    # print "\n";

    $snmp_sess->close();
    print "\n";
}

$dbh->disconnect();
