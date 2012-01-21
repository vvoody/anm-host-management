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

Type: unknown(1), operatingSystem(2), deviceDriver(3), application(4)<br />
Status: running(1), runnable(2), notRunnable(3), invalid(4)

<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>Name</th>
<th>Type</th>
<th>Status</th>
<th>Host ID</th>
<th>Graph</th>
</tr>

<?php

foreach ($softwarerunning as $row) {
    $id = $row->id;
    $name = $row->name;
    $type = $row->type;
    $status = $row->status;
    $hid = $row->host_id;
    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td>$type</td>";
    echo "<td>$status</td>";
    echo "<td>$hid</td>";
    echo "<td>" . anchor("softwarerunning/graph/daily/$id", 'Daily') . "&bull;" . anchor("softwarerunning/graph/weekly/$id", 'Weekly') . "&bull;" . anchor("softwarerunning/graph/monthly/$id", 'Monthly') . "</td>";
    echo "</tr>";
}

echo "</table>";
require_once 'footer.php';
?>