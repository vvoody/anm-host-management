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


Status: unknown(1), running(2), warning(3), testing(4), down(5)

<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>ID</th>
<th>Descr</th>
<th>Type</th>
<th>Status</th>
<th>Host ID</th>
<th>Device IDX</th>
<th>Available</th>
<th>Graph</th>
</tr>

<?php

foreach ($devices as $row) {
    $id = $row->id;
    $descr = $row->descr;
    $type = $row->type;
    $status = $row->status;
    $hid = $row->host_id;
    $did = $row->device_idx;
    $available = $row->available == 1 ? "YES" : "NO";
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$descr</td>";
    echo "<td>$type</td>";
    echo "<td>$status</td>";
    echo "<td>$hid</td>";
    echo "<td>$did</td>";
    echo "<td>$available</td>";
    echo "<td>" . anchor("device/graph/daily/$id", 'Daily') . "&bull;" . anchor("device/graph/weekly/$id", 'Weekly') . "&bull;" . anchor("device/graph/monthly/$id", 'Monthly') . "</td>";
    echo "</tr>";
}

echo "</table>";
require_once 'footer.php';
?>