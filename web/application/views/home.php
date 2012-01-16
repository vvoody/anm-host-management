<?php 
require_once 'header.php';
require_once 'navigation.php';
?>

<h2>You have:</h2>
<table border="0" width="40%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>Level</th>
<th>Amount</th>
</tr>
<tr>
  <td>WARNING</td>
  <td><?php echo anchor('stats/index/warning', $warnings, 'style="text-decoration:underline;"');?></td>
</tr>
<tr>
  <td>ERROR</td>
  <td><?php echo anchor('stats/index/error', $errors, 'style="text-decoration:underline;"');?></td>
</tr>
<tr>
  <td>CRITICAL</td>
  <td><?php echo anchor('stats/index/critical',$criticals, 'style="text-decoration:underline;"');?></td>
</tr>
</table>

<?php
require_once 'footer.php';
?>
