<?php
require_once 'header.php';
require_once 'navigation.php';

echo form_open('host/add');

?>

<table class="login">

<tr>
<td>Add Host IP:</td>
<td><input type="text" name="ip" /></td>
</tr>

<tr>
<td>Community:</td>
<td><input type="text" name="community" /></td>
</tr>

<tr>
<td>&nbsp;</td> 
<td><input type="submit" value="Approve"/></td>
</tr>

</table>

</form>

<?php
require_once 'footer.php';
?>
