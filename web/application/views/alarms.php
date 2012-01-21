<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
  <tr>
    <th>Alarm ID</th>
    <th>Content</th>
    <th>Status</th>
    </tr>
<?php
$isAdmin = $this->session->userdata('isAdmin') ? TRUE : FALSE;

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
    $solved = $row->solved;
    echo "<tr>";
    echo "<td>$id</td>";
    switch ($component) {
        case 'device':
        case 'storage':
            echo "<td>$component " . anchor("$component/showall/$host_id", $cmpt_idx, 'id="url_underline"') . " of host " . anchor("host/$host_id", $host_id, 'id="url_underline"') . " $event</td>";
            break;
        case 'host':
            echo "<td>$component $comment $event</td>";
            break;
        default:
            echo "<td>$component $event</td>";
    }
    if ($isAdmin && $solved == 0)
        echo '<td>Not solved, ' . anchor("stats/solve/$id", 'make it solved...', 'id="url_underline"') . "</td>";
    else
        echo "<td>Already solved.</td>";
    echo "</tr>";
}
?>
</table>
