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
<th>Host ID</th>
<th>Uptime</th>
<th>Users</th>
<th>Max Processes</th>
<th>Memory Size</th>
<th>Loaded Processes</th>
<th>Host Monitoring</th>
<th>Remove Host</th>
</tr>


<?php

?>

</table>

<?php 
require_once 'footer.php';
?>