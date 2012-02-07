<!DOCTYPE HTML>

<html>
<head>
  <meta charset="utf-8" />
  <title>Host Management System <?php echo "- " . $title; ?></title>
  <link rel="stylesheet" href="<?php echo base_url('css/main.css');?>" />
  <link rel="stylesheet" href="<?php echo base_url('css/jquery-ui-datepicker.css');?>" />
  <link rel='stylesheet' href="http://fonts.googleapis.com/css?family=Ubuntu+Condensed" />
  <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
  <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.flot.js');?>"></script>
  <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery-ui-datepicker.js');?>"></script>
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
      <div id="datepicker"></div>
      <h1 id="h1_title_no_right"><?php echo $title;?></h1><br />
      <?php
          foreach ($graphs as $g) {
              echo "<div id=\"placeholder_$g\" style=\"width:600px;height:300px;\"></div>\n";
          }
      ?>


<script type="text/javascript">

// Datepicker
$('#datepicker').datepicker({
    inline: true
});

$(function () {
    var options = {
        lines: { show: true },
        points: { show: true },
        xaxis: { mode : "time", timeformat: <?php echo $period == 'daily' ? 'null' : '"%b %d"';?> }
    };
    var data = [];

    // var placeholder will be set by the function in ajax success.
        
    // then fetch the data with jQuery
    function onDataReceived(series) {
        // extract the first coordinate pair so you can see that
        // data is now an ordinary Javascript object

        // and plot all we got
        data.push(series);

        $.plot($('#'+placeholder), data, options);
        data.pop();
    }

    <?php
        foreach ($graphs as $g) {
            echo "var dataurl_$g='" . site_url("ajax/json/$component/$g/$period/$id_or_name") . "';\n";
            echo "$.ajax({url: dataurl_$g, method: 'GET', dataType: 'json', success: [function() {placeholder='placeholder_$g';}, onDataReceived]});\n";
        }
    ?>

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
