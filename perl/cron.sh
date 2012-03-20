#!/usr/bin/env bash

# script to run perl scripts in sequence under cron

LOGFILE="/tmp/anm-host-management/anm-host-management.log"
LOCKFILE="/tmp/anm-host-management/cron.lock"

mylog() {
    echo $(date) " - $1" | tee -a $LOGFILE
}

run_script() {
    echo "******************** $1 ********************"
    perl $1
    if [ $? -ne 0 ]; then
        mylog "ERROR: $1 failed."
    fi
    echo "****************** $1 END ******************"
}

mylog "perl/cron.sh started."
(
    if ! flock -n -x 100; then
        mylog "Another cron.sh is running..."
        exit 1
    fi
    run_script hosts_log.pl
    run_script statistics.pl
    run_script devices_log.pl
    run_script storage_log.pl
    run_script software_running_log.pl
    run_script software_installed_log.pl
    run_script alarms.pl
) 100>$LOCKFILE
mylog "perl/cron.sh ended."
