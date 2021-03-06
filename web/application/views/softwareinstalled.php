<?php
require_once 'header.php';
require_once 'navigation.php';
echo "<div class=\"con_link\">";
echo anchor("device/showall/$host_id", 'Devices') . "<span>&bull;</span>";
echo anchor("storage/showall/$host_id", 'Storage') . "<span>&bull;</span>";
echo anchor("softwareinstalled/showall/$host_id", 'Installed Software') . "<span>&bull;</span>";
echo anchor("softwarerunning/showall/$host_id", 'Runnning Software');
echo "</div>";
?>

Type: unknown(1), operatingSystem(2), deviceDriver(3), application(4)
<br />
<b>Note:</b> You may not get this type of information in some system like Slackware and Ubuntu,
<a href="http://community.zenoss.org/message/51022">reasons</a>.

<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>ID</th>
<th>Name</th>
<th>Type</th>
<th>Host ID</th>
<th>Last Update</th>
</tr>

<?php

foreach ($softwareinstalled as $row) {
    $id = $row->id;
    $name = $row->name;
    $type = $row->type;
    $hid = $row->host_id;
    $last_update = $row->last_update;
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$name</td>";
    echo "<td>$type</td>";
    echo "<td>$hid</td>";
    echo "<td>$last_update</td>";
    echo "</tr>";
}

echo "</table>";
require_once 'footer.php';
?>