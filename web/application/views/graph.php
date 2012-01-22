<!DOCTYPE HTML>

<html>
<head>
  <meta charset="utf-8" />
  <title>Host Management System <?php echo "- " . $title; ?></title>
  <link rel="stylesheet" href="<?php echo base_url('css/main.css');?>" />
  <link rel='stylesheet' href="http://fonts.googleapis.com/css?family=Ubuntu+Condensed" />
  <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
  <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.flot.js');?>"></script>
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
      <h1><?php echo $title;?></h1>
      <div id="placeholder" style="width:600px;height:300px;"></div>

    <p>Simple example.</p>

<script type="text/javascript">
$(function () {
    var options = {
        lines: { show: true },
        points: { show: true },
        xaxis: { mode : "time" } //tickDecimals: 0, tickSize: 1, mode: "time" }
    };
    var data = [];
    var placeholder = $("#placeholder");
        
    // fetch one series, adding to what we got
    
    var dataurl = '<?php echo site_url("ajax/json/$component/$period/$id_or_name");?>';

    // then fetch the data with jQuery
    function onDataReceived(series) {
        // extract the first coordinate pair so you can see that
        // data is now an ordinary Javascript object
        
        // and plot all we got
        data.push(series);
        $.plot(placeholder, data, options);
    }

    $.ajax({
        url: dataurl,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
    });
});
</script>

    </div>
  </div>

<div class="footer">
  <div class="container">
  <p>Copyright &copy; BTH</p>
</div>

</body>
</html>
