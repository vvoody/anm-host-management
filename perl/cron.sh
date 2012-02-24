#!/usr/bin/env bash

# script to run perl scripts in sequence under cron

LOGFILE="/tmp/anm-host-management.log"

mylog() {
    echo $(date) " - $1" | tee -a $LOGFILE
}

run_script() {
    echo "******************** $1 ********************"
    perl $1 || mylog "ERROR: $1 failed."
    echo "****************** $1 END ******************"
}

mylog "perl/cron.sh started."

run_script hosts_log.pl
run_script statistics.pl
run_script devices_log.pl
run_script storage_log.pl
run_script software_running_log.pl
run_script software_installed_log.pl
run_script alarms.pl

mylog "perl/cron.sh ended."
