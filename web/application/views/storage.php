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




<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>ID</th>
<th>Descr</th>
<th>Type</th>
<th>Size</th>
<th>Allocated Sectors</th>
<th>Host ID</th>
<th>Storage IDX</th>
<th>Available</th>
<th>Graph</th>
</tr>

<?php

foreach ($storage as $row) {
    $id = $row->id;
    $descr = $row->descr;
    $type = $row->type;
    $size = $row->size;
    $allocated_sectors = $row->allocated_sectors;
    $hid = $row->host_id;
    $storage_idx = $row->storage_idx;
    $available = $row->available == 1 ? "YES" : "NO";
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$descr</td>";
    echo "<td>$type</td>";
    echo "<td>$size</td>";
    echo "<td>$allocated_sectors</td>";
    echo "<td>$hid</td>";
    echo "<td>$storage_idx</td>";
    echo "<td>$available</td>";
    echo "<td>" . anchor("storage/graph/daily/$id", 'Daily') . "&bull;" . anchor("storage/graph/weekly/$id", 'Weekly') . "&bull;" . anchor("storage/graph/monthly/$id", 'Monthly') . "</td>";
    echo "</tr>";
}

echo "</table>";
require_once 'footer.php';
?>