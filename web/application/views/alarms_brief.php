<ul>

<?php
//echo $this->table->generate($results);
$there_are_alarms = FALSE;
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
    echo '<li id="li_disc">';
    switch ($component) {
        case 'device':
        case 'storage':
            echo "$component $cmpt_idx of host $host_id $event ";
            break;
        case 'host':
            echo "$component $comment $event ";
            break;
        default:
            echo "$component $event ";
    }
    echo " -> " . anchor("stats/show/$id", 'check');
    echo "</li>";
    $there_are_alarms = TRUE;
}

if (! $there_are_alarms)
   echo "<li>No alarms, everything is OK.</li>";

?>

</ul>
