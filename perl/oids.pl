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

$hrSWInstalledIndex          = "1.3.6.1.2.1.25.6.3.1.1";
$hrSWInstalledName           = "1.3.6.1.2.1.25.6.3.1.2";
$hrSWInstalledType           = "1.3.6.1.2.1.25.6.3.1.4";
$hrSWInstalledDate           = "1.3.6.1.2.1.25.6.3.1.5";

$hrSWOSIndex          = "1.3.6.1.2.1.25.4.1.0";
$hrSWRunTable         = "1.3.6.1.2.1.25.4.2";
$hrSWRunEntry         = "1.3.6.1.2.1.25.4.2.1";
$hrSWRunIndex         = "1.3.6.1.2.1.25.4.2.1.1";    # pid
$hrSWRunName          = "1.3.6.1.2.1.25.4.2.1.2";
$hrSWRunType          = "1.3.6.1.2.1.25.4.2.1.6";
$hrSWRunStatus        = "1.3.6.1.2.1.25.4.2.1.7";

$hrSWRunPerf          = "1.3.6.1.2.1.25.5";
$hrSWRunPerfTable     = "1.3.6.1.2.1.25.5.1";
$hrSWRunPerfEntry     = "1.3.6.1.2.1.25.5.1.1";
$hrSWRunPerfCPU       = "1.3.6.1.2.1.25.5.1.1.1";
$hrSWRunPerfMem       = "1.3.6.1.2.1.25.5.1.1.2";
