#!/usr/bin/env bash

# setup script for ANM Host Management System

SQLFILE="doc/db.sql"
PERL_SCRIPTS="perl/"
WEB_SCRIPTS="web/"

ADMIN="root"
ADMINPW="toor"

WEB_DBCONF="web/application/config/database.php"
WEB_CONF="web/application/config/config.php"
PERL_CONF="perl/setting.pl"

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
}

set -eu

case "$1" in
    clean)
        echo "Doing cleanup jobs: drop tables,"
        get_db_userpasswd
        clean_database
        ;;
    install)
        echo "Initialize database..."
        get_all_info
        deploy_database
        db_create_root
        deploy_files
        setup_web
        setup_perl
        echo -e "\nDone."
        echo "Please use '$ADMIN' with password '$ADMINPW' to login."
        ;;
    *)
        echo "Unknown instruction '$1'"
        echo "Usage: $0 install|clean"
        exit 1
        ;;
esac
