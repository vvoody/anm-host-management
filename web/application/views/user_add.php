<?php 
require_once 'header.php';
require_once 'navigation.php';

echo form_open('user/add');

?>

<table class="login">

<tr>
<td>Name:</td>
<td><input type="name" name="name" /></td>
</tr>

<tr>
<td>Username:</td>
<td> <input type="name" name="username" /></td>
</tr>

<tr>
<td>Password:</td>
<td><input type="password" name="password" /></td>
</tr>

<tr>
<td>Email:</td>
<td><input type="text" name="email" /></td>
</tr>

<tr>
<td>Account Type:</td>
<td><select name="user">
<option>User</option>
<option>Admin</option>
</select></td>
</tr>

<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Add"/></td>
</tr>

</table>

</form>

<?php
require_once 'footer.php';
?>
