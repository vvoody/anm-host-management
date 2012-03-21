<!DOCTYPE HTML>

<html>
<head>
  <meta charset="utf-8" />
  <title>Host Management System <?php echo "- " . $title; ?></title>
  <link rel="stylesheet" href="<?php echo base_url('css/main.css');?>" />
  <link rel='stylesheet' href="http://fonts.googleapis.com/css?family=Ubuntu+Condensed" />
</head>

<body>
  <div class="header">
    <div class="container">
      <h1>Host Management System</h1>
    </div>
  </div>

  <div class="nav">
    <div class="container">
      <?php
      echo anchor('', 'Home');
      echo "<span>&bull;</span>";
      echo anchor('stats/', 'Statistics');
      echo "<span>&bull;</span>";
      echo anchor('host/show/all', 'Hosts');
      echo "<span>&bull;</span>";
      echo anchor('user/show/all', 'Users');
      echo "<span>&bull;</span>";
      echo anchor('master/logout', 'Logout');
      ?>
    </div>
  </div>

  <div class="content">
    <div class="container">
      <h1 id="h1_title_no_right"><?php echo $title;?></h1><br />
      <?php
          foreach ($graphs as $g) {
              echo "<div id=\"placeholder_$g\">";  // style=\"width:600px;height:300px;\"
              echo "<img src=\"" . site_url() . "/rrd/graph/$component/$g/$period/$id_or_name" . "\" alt=\"$g\" />";
              echo"</div>\n";
          }
      ?>
    </div>
  </div>

<div class="footer">
  <div class="container">
  <p>Copyright &copy; BTH</p>
</div>

</body>
</html>
