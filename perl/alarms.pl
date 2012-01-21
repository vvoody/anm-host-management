#!/usr/bin/env perl

# alarm if some defined thresholds triggered.

require "common.pl";
require "oids.pl";
use lib "lib/";
use Set::Scalar;
use Data::Dumper;


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
                    $field_values = {"component" => "storage",
                                     "cmpt_idx" => $storage_idx,
                                     "event" => "space used over " . $USED_THRESHOLD * 100 . "%",
                                     "host_id" => $host_id,
                                     "level" => 2,              # WARNING
                    };
                    my $st = insert_hash($dbh, "statistics", $field_values);
                    &MYLOG($0, "insert_hash", "statistics|$field_values", "insert failed") if !defined $st;
                }
                last;
            }
        }
    }
}


my ($dbh, $err_db) = connect_db();
die $err_db if $err_db;
my ($hosts_ref, $err_hosts) = get_hosts($dbh);
die $err_hosts if $err_hosts;

foreach $host (@$hosts_ref) {
    check_storage($host);
}
