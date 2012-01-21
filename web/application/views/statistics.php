<?php 
require_once 'header.php';
require_once 'navigation.php';
?>

<?php
echo 'Level';
echo "<span>&nbsp;</span>";

echo anchor('stats/index/', 'Not Solved', 'id="not_solved"');
echo "<span>&bull;</span>";

echo anchor('stats/index/log', 'LOG');
echo "<span>&bull;</span>";

echo anchor('stats/index/notice', 'NOTICE');
echo "<span>&bull;</span>";

echo anchor('stats/index/warning', 'WARNING');
echo "<span>&bull;</span>";

echo anchor('stats/index/error', 'ERROR');
echo "<span>&bull;</span>";

echo anchor('stats/index/critical', 'CRITICAL');
?>

<?php
require_once 'alarms.php';
?>

<?php
if ($this->uri->segment(2) != "show")
    echo $this->pagination->create_links();
//echo $debug_info;
require_once 'footer.php';
?>
