$hrDeviceTable        = "1.3.6.1.2.1.25.3.2";        # baseoid
$hrDeviceIndex        = "1.3.6.1.2.1.25.3.2.1.1";
$hrDeviceType         = "1.3.6.1.2.1.25.3.2.1.2";
$hrDeviceDescr        = "1.3.6.1.2.1.25.3.2.1.3";
$hrDeviceStatus       = "1.3.6.1.2.1.25.3.2.1.5";    # not every entry has this
$hrDeviceErrors       = "1.3.6.1.2.1.25.3.2.1.6";    # same as above

$hrStorageTable              = "1.3.6.1.2.1.25.2.3";
$hrStorageIndex              = "1.3.6.1.2.1.25.2.3.1.1";
$hrStorageType               = "1.3.6.1.2.1.25.2.3.1.2";
$hrStorageDescr              = "1.3.6.1.2.1.25.2.3.1.3";
$hrStorageAllocationUnits    = "1.3.6.1.2.1.25.2.3.1.4";
$hrStorageSize               = "1.3.6.1.2.1.25.2.3.1.5";
$hrStorageUsed               = "1.3.6.1.2.1.25.2.3.1.6";
$hrStorageAllocationFailures = "1.3.6.1.2.1.25.2.3.1.7";    # can get, but is hrDeviceIndex