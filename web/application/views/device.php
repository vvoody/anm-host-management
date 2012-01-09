<?php
require_once 'header.php';
require_once 'navigation.php';
echo "<div class=\"con_link\">";
echo anchor('device/showall/1', 'Devices') . "<span>&bull;</span>";
echo anchor('storage/showall/1', 'Storage') . "<span>&bull;</span>";
echo anchor('softwareinstalled/showall/1', 'Installed Software') . "<span>&bull;</span>";
echo anchor('softwarerunning/showall/1', 'Runnning Software');
echo "</div>";
?>




<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>Host ID</th>
<th>Type</th>
<th>Description</th>
<th>Status</th>
<th>Graph</th>
</tr>


<?php

echo "</table>";
require_once 'footer.php';
?>