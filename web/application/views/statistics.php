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

<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
  <tr>
    <th>id</th>
    <th>component</th>
    <th>event</th>
    <th>pre_value</th>
    <th>now_value</th>
    <th>comment</th>
    <th>stamp</th>
    <th>level</th>
    <th>tid</th>
    <th>cmpt_idx</th>
    <th>host_id</th>
    <th>solved</th>
    </tr>
<?php
//echo $this->table->generate($results);
foreach ($results as $row) {
    $id = $row->id;
    $component = $row->component;
    $event = $row->event;
    $pre_value = $row->pre_value;
    $now_value = $row->now_value;
    $comment = $row->comment;
    $stamp = $row->stamp;
    $level = $row->level;
    $tid = $row->tid;
    $cmpt_idx = $row->cmpt_idx;
    $host_id = $row->host_id;
    $solved = $row->solved == 1 ? "YES" : "NO";
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$component</td>";
    echo "<td>$event</td>";
    echo "<td>$pre_value</td>";
    echo "<td>$now_value</td>";
    echo "<td>$comment</td>";
    echo "<td>$stamp</td>";
    echo "<td>$level</td>";
    echo "<td>$tid</td>";
    echo "<td>$cmpt_idx</td>";
    echo "<td>$host_id</td>";
    echo "<td>$solved</td>";
    echo "</tr>";
}
?>
</table>

<?php
echo $this->pagination->create_links();
//echo $debug_info;
require_once 'footer.php';
?>
