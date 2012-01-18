<?php 
require_once 'header.php';
require_once 'navigation.php';
?>

<?php
echo 'Level';
echo "<span>&nbsp;</span>";

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
echo $this->table->generate($results);
echo $this->pagination->create_links();

echo $debug_info;

require_once 'footer.php';
?>
