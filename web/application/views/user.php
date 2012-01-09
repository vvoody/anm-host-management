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
<th>ID</th>
<th>Username</th>
<th>Account Type</th>
<th>Email</th>
<th>Password</th>
<th>Remove User</th>
</tr>


<?php

foreach ($users as $row) {
    $id = $row->id;
    $username = $row->username;
    $account_type = $row->accountype;
    $email = $row->email;
    $password = $row->password;
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$username</td>";
    echo "<td>$account_type</td>";
    echo "<td>$email</td>";
    echo "<td>$password</td>";
    echo "<td>DELETE</td>";
    echo "</tr>";
}

?>

</table>
</form>

<?php 
require_once 'footer.php';
?>
