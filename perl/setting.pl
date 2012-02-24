sub assert {
    die "Please set '$_[0]'\n";
}

$DATABASE = "" or assert "DATABASE";
$HOST     = "localhost" or assert "HOST";
$DBUSER   = "" or assert "DBUSER";
$DBPASSWD = "" or assert "DBPASSWD";

$LOGFILE  = "/tmp/anm-host-management.log" or assert "LOGFILE";
