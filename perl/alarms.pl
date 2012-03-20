#!/usr/bin/env perl

# alarm if some defined thresholds triggered.

require "common.pl";
require "oids.pl";
use lib "lib/";
use Set::Scalar;
#use Data::Dumper;


sub check_storage {
    my $host = $_[0];
    my ($host_id, $ip, $community) = @$host;
    my $USED_THRESHOLD = 0.9;  # 90%

    my ($l_ref, $err) = select_table_cols($dbh, "storage", ["id, storage_idx, size"], $host_id);
    &MYLOG($0, "select_table_cols", "storage_idx", $err) if $err;
    return undef if !defined $l_ref;

    # hrStorageUsed / hrStorageSize > $USED_THRESHOLD then alarm
    # [id, storage_idx, size]
    foreach $r (@$l_ref) {
        my ($storage_id, $storage_idx, $size) = @$r;
        my ($rows_ref, $err_rows) = get_rows($dbh, "SELECT used_capacity FROM storage_log WHERE storage_id = $storage_id order by stamp desc limit 1");
        if ($rows_ref) {
            foreach $row (@$rows_ref) {
                my ($used_capacity) = @$row;
                if ($used_capacity && ($used_capacity / $size) > $USED_THRESHOLD) {
                    print STDERR "storage $storage_idx of host $host_id has no enough space!\n";
                    $event = "space used over " . $USED_THRESHOLD * 100 . "%";
                    $field_values = {"component" => "storage",
                                     "cmpt_idx" => $storage_idx,
                                     "event" => $event,
                                     "host_id" => $host_id,
                                     "level" => 2,              # WARNING
                    };
                    my ($rows_ref, $err_rows) = get_rows($dbh, "select * from statistics where solved = 0 and host_id = $host_id and component = 'storage' and cmpt_idx = $storage_idx and event = '$event' and level = 2");
                    if (! @$rows_ref) {  # not alarmed before
                        print "Alarm!\n";
                        my $st = insert_hash($dbh, "statistics", $field_values);
                        &MYLOG($0, "insert_hash", "statistics|$field_values", "insert failed") if !defined $st;
                    }
                }
                last;
            }
        }
    }
}


sub check_host {
    my $host = $_[0];
    my ($host_id, $ip, $community) = @$host;
    my $max_time_diff = 20 * 60;  # 20 minutes by default

    my $sth = $dbh->prepare("select hosts.ip_name, hosts_log.stamp from hosts_log, hosts where hosts_log.host_id = hosts.id and hosts_log.host_id = $host_id order by hosts_log.stamp desc limit 1;");
    $rows = $dbh->selectall_arrayref($sth);

    if (@$rows) {
        foreach $row (@$rows) {
            my ($ip, $stamp) = @$row;
            my $last_stamp = `date -d '$stamp' +%s`;
            my $now = localtime;
            my $now_stamp = `date -d '$now' +%s`;
            my $diff = $now_stamp - $last_stamp;
            if (abs($diff) > $max_time_diff) {
                my $comment = "$ip, in last at least $max_time_diff seconds, ";
                my $event = "not reachable!";
                print STDERR $comment . $event . "\n";
                my $sql = "select * from statistics where solved = 0 and host_id = $host_id and component = 'host' and event = '$event' and level = 3";
                my ($rows_ref, $err_rows) = get_rows($dbh, $sql);
                if (! @$rows_ref) {  # not alarmed before
                    print "Alarm!\n";
                    $field_values = {"component" => "host",
                                     "event" => $event,
                                     "host_id" => $host_id,
                                     "comment" => $comment,
                                     "level" => 3,              # ERROR
                    };
                    my $st = insert_hash($dbh, "statistics", $field_values);
                    &MYLOG($0, "insert_hash", "statistics|$field_values", "insert failed") if !defined $st;
                }
            }
            last;
        }
    }
}


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;

foreach $host (@$hosts_ref) {
    check_storage($host);
    check_host($host);
}
