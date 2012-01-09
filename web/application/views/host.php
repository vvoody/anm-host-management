<?php
require_once 'header.php';
require_once 'navigation.php';

echo "<h1 class=\"heading\">Host Management</h1>";

echo "<div class=\"con_link\">";
echo anchor('host/add', 'Add Host', 'class="add"');
echo "</div>";
?>

<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>ID</th>
<th>IP</th>
<th>community</th>
<th>Monitoring</th>
<th>Remove Host</th>
</tr>


<?php

foreach ($hosts as $row) {
    $id = $row->id;
    $ip = $row->ip_name;
    $community = $row->community;
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$ip</td>";
    echo "<td>$community</td>";
    echo "<td>" . anchor("host/show/$id", "Monitoring") . "</td>";
    echo "<td>" . anchor("host/del/$id", 'DELETE') . "</td>";
    echo "</tr>";
}

?>

</table>

<?php 
require_once 'footer.php';
?>