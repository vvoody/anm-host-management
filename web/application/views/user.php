<?php
require_once 'header.php';
require_once 'navigation.php';

echo "<h1 class=\"heading\">User Management</h1>";

echo "<div class=\"con_link\">";
echo anchor('user/add', 'Add User', 'class="add"');
echo "</div>";
?>

<form>
<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
<tr>
<th>Name</th>
<th>Username</th>
<th>Account Type</th>
<th>Email</th>
<th>Remove User</th>
</tr>

</table>
</form>

<?php 
require_once 'footer.php';
?>
