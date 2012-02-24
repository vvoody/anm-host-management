sub assert {
    die "Please set '$_[0]'\n";
}

$DATABASE = "YOUR DATABASE NAME" or assert "DATABASE";
$HOST     = "localhost" or assert "HOST";
$DBUSER   = "YOUR DATABASE USER NAME" or assert "DBUSER";
$DBPASSWD = "YOUR DATABASE PASSWORD" or assert "DBPASSWD";

$LOGFILE  = "/tmp/anm-host-management.log" or assert "LOGFILE";
