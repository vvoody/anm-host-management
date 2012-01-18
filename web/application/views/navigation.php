<div class="nav">

<div class="container">

<?php

echo anchor('', 'Home');

echo "<span>&bull;</span>";

echo anchor('stats/', 'Statistics');

echo "<span>&bull;</span>";

if ($this->session->userdata('isAdmin') == TRUE) {
    echo anchor('host/show/all', 'Hosts');
    
    echo "<span>&bull;</span>";
    
    echo anchor('user/show/all', 'Users');
    
    echo "<span>&bull;</span>";
}

echo anchor('master/logout', 'Logout');

?>

</div>

</div>

<div class="content">

<div class="container">
