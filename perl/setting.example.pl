sub assert {
    die "Please set '$_[0]'\n";
}

$DATABASE = "YOUR DATABASE NAME" or assert "DATABASE";
$HOST     = "localhost" or assert "HOST";
$DBUSER   = "YOUR DATABASE USER NAME" or assert "DBUSER";
$DBPASSWD = "YOUR DATABASE PASSWORD" or assert "DBPASSWD";

$TMP_DIR  = "/tmp/anm-host-management/" or assert "TMP_DIR";
$RRD_DIR  = $TMP_DIR . "rrd/" or assert "RRD_DIR";
$LOGFILE  = $TMP_DIR . "/anm-host-management.log" or assert "LOGFILE";
