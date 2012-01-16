<?php
require_once 'header.php';
require_once 'navigation.php';
?>

<?php
echo "<h1 class=\"heading\">Name of the Host</h1>";
echo "<ul>";
echo "<li class=\"dev\">" . anchor("device/showall/$host_id", 'Devices') . "</li>";
echo "<li class=\"sto\">". anchor("device/showall/$host_id", 'Storage') . "</li>";
echo "<li class=\"isft\">" . anchor("device/showall/$host_id", 'Installed Software') . "</li>";
echo "<li class=\"rsft\">" . anchor("device/showall/$host_id", 'Runnning Software') . "</li>";
echo "</ul>";

require_once 'footer.php';
?>