<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
  <tr>
    <th>Alarm ID</th>
    <th>Content</th>
<?php $show_action = FALSE;
      if ($alarm_level == 'only_notsolved' && $this->session->userdata('isAdmin') == TRUE) {
          echo "<th>Action</th>";
          $show_action = TRUE;
      }
?>
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
    if ($alarm_level == 'only_notsolved' && $show_action)
        echo '<td>' . anchor("stats/solve/$id", 'I know it, problem solved.', 'id="url_underline"') . "</td>";
    echo "</tr>";
}
?>
</table>
