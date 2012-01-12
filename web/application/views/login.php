<?php
require_once("header.php");
?>

<div class="content">

<div class="container">

<?php echo form_open('master/logincheck'); ?>
 
<table class="login">

<tr>
<td>Username:</td>
<td><input type="name" name="username" /></td>
</tr>

<tr>
<td>Password:</td>
<td><input type="password" name="password" /></td>
</tr>

<tr>
<td>Account:</td>
<td><select name="account_type">
<option>User</option>
<option>Admin</option>
</select>
<input type="submit" value="Login"/>
</td>
</tr>

<tr>
<td class="warning" colspan="2"><?php echo isset($warning)?$warning:'';?></td>
</tr>

</table>

</form>

</div>

</div>

<?php
require_once("footer.php");
?>
