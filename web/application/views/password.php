<?php 
require_once 'header.php';
require_once 'navigation.php';

echo form_open('master/password/change');

?>

<table class="login">

<tr>
<td>Old password:</td>
<td><input type="password" name="old_password" /></td>
</tr>

<tr>
<td>New password:</td>
<td> <input type="password" name="new_password" /></td>
</tr>

<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Change"/></td>
</tr>

<tr>
<td class="warning" colspan="2"><?php echo isset($warning)?$warning:'';?></td>
</tr>

</table>

</form>

<?php
require_once 'footer.php';
?>
