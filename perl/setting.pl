sub assert {
    die "Please '$_[0]'\n";
}

$DATABASE = "" or assert "DATABASE";
$HOST     = "" or assert "HOST";
$DBUSER   = "" or assert "DBUSER";
$DBPASSWD = "" or assert "DBPASSWD";

$LOGFILE  = "/tmp/anm-host-management.log" or assert "LOGFILE";
