#!/usr/bin/env bash

# setup script for ANM Host Management System

# reduce less user inputing DBNAME, DBUSER, DBPASSWD, DEPLOY_PATH
LAST_SAVED=".last_saved"

SQLFILE="doc/db.sql"
PERL_SCRIPTS="perl/"
WEB_SCRIPTS="web/"

ADMIN="root"
ADMINPW="toor"

WEB_DBCONF="web/application/config/database.php"
WEB_CONF="web/application/config/config.php"
PERL_CONF="perl/setting.pl"

# keep same with perl/setting.example.pl
PERL_TMP_DIR="/tmp/anm-host-management";
PERL_RRD_DIR="${TMP_DIR}/rrd";

deploy_database() {
    cat $SQLFILE | mysql -u$DBUSER -p$DBPASSWD $DBNAME
}

clean_database() {
    mysqldump -u$DBUSER -p$DBPASSWD --add-drop-table --no-data $DBNAME | grep ^DROP | mysql -u$DBUSER -p$DBPASSWD $DBNAME
}

deploy_files() {
    cp -rf $PERL_SCRIPTS $WEB_SCRIPTS $DEPLOY_PATH
    chmod -R o-rwx $DEPLOY_PATH/$PERL_SCRIPTS
}

import_last_saved() {
    if [ -f $LAST_SAVED ]; then
        source $LAST_SAVED
        echo "DBNAME=$DBNAME, DBUSER=$DBUSER, DBPASSWD=$DBPASSWD, DEPLOY_PATH=$DEPLOY_PATH"
        if [ -n "$DBNAME" ] &&
            [ -n "$DBUSER" ] &&
            [ -n "$DBPASSWD" ] &&
            [ -n "$DEPLOY_PATH" ] ; then
            echo "INFO: imported configs from $LAST_SAVED"
            return 0
        else
            echo "WARNING: $LAST_SAVED is not set correctly, removed."
            rm -f $LAST_SAVED
        fi
    fi
    return 1
}

get_db_userpasswd() {
    read -e -p "Database name: " DBNAME
    read -e -p "Database username: " DBUSER
    read -e -s -p "Database password: " DBPASSWD
}

get_deploy_path() {
    read -e -p "Full path where you want to deploy: " DEPLOY_PATH
}

get_all_info() {
    get_deploy_path
    get_db_userpasswd
}

db_create_root() {
    echo "INSERT INTO users (username, password, account_type, name) VALUES ('$ADMIN', ENCRYPT('$ADMINPW'), 'Admin', 'root')" | mysql -u$DBUSER -p$DBPASSWD $DBNAME
}

setup_web() {
    sed -e "s/\$db\['default'\]\['username'\] = '';/\$db\['default'\]\['username'\] = '$DBUSER';/" \
        -e "s/\$db\['default'\]\['password'\] = '';/\$db\['default'\]\['password'\] = '$DBPASSWD';/" \
        -e "s/\$db\['default'\]\['database'\] = '';/\$db\['default'\]\['database'\] = '$DBNAME';/" \
        -i $DEPLOY_PATH/$WEB_DBCONF

    encryption_key=$(date +%s | md5sum | cut -d ' ' -f1)
    sed -e "s/\$config\['encryption_key'\] = '';/\$config\['encryption_key'\] = '$encryption_key';/" \
        -i $DEPLOY_PATH/$WEB_CONF
}

setup_perl() {
    sed -e "s/\$DATABASE[ ]* = \"\" or assert \"DATABASE\";/\$DATABASE = \"$DBNAME\" or assert \"DATABASE\";/" \
        -e "s/\$DBUSER[ ]*= \"\" or assert \"DBUSER\";/\$DBUSER = \"$DBUSER\" or assert \"DBUSER\";/" \
        -e "s/\$DBPASSWD[ ]*= \"\" or assert \"DBPASSWD\";/\$DBPASSWD = \"$DBPASSWD\" or assert \"DBPASSWD\";/" \
        -i $DEPLOY_PATH/$PERL_CONF
    mkdir -p $PERL_RRD_DIR
}

set -e

case "$1" in
    clean)
        echo "Doing cleanup jobs: drop tables,"
        import_last_saved || get_db_userpasswd
        clean_database
        ;;
    install)
        echo "Starting install..."
        import_last_saved || get_all_info
        deploy_database
        db_create_root
        deploy_files
        setup_web
        setup_perl
        echo -e "\nDone."
        echo "Please use '$ADMIN' with password '$ADMINPW' to login."
        echo "And add following line to your crontab(crontab -e):"
        echo '*/5 * * * *' "${DEPLOY_PATH}/${PERL_SCRIPTS}/cron.sh"
        ;;
    test)
        echo "Testing..."
        import_last_saved || get_all_info
#        exit 0
        ;;
    *)
        echo "ERROR: Unknown instruction '$1'"
        echo "Usage: $0 install|clean"
        exit 1
        ;;
esac

(cat <<EOF
DBNAME=$DBNAME
DBUSER=$DBUSER
DBPASSWD=$DBPASSWD
DEPLOY_PATH=$DEPLOY_PATH
EOF
) > $LAST_SAVED
