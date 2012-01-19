<div id="maincontainer">
  <div id="main_pane_left">
    <div class="group">
      <h2>PAY ATTENTION!</h2>
      <ul>
        <li>
          <?php require_once 'alarms.php'; ?>
        </li>
        <li>
          <table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">
          <tr>
            <td id="td_notice">NOTICE</td>
            <td id="td_warning">WARNING</td>
            <td id="td_error">ERROR</td>
            <td id="td_critical">CRITICAL</td>
          </tr>
          <tr>
            <td><?php echo anchor('stats/index/notice', $notices, 'style="text-decoration:underline;"');?></td>
            <td><?php echo anchor('stats/index/warning', $warnings, 'style="text-decoration:underline;"');?></td>
            <td><?php echo anchor('stats/index/error', $errors, 'style="text-decoration:underline;"');?></td>
            <td><?php echo anchor('stats/index/critical',$criticals, 'style="text-decoration:underline;"');?></td>
          </tr>
          </table>
        </li>
      </ul>
    </div>
<!--    <div class="group">       -->
<!--      <h2>General Status</h2> -->
<!--      <ul>                    -->
<!--        <li>blablabla</li>    -->
<!--      </ul>                   -->
<!--    </div>                    -->
  </div>
  <div id="main_pane_right">
    <div class="group">
      <h2>Personal Setting</h2>
      <ul>
        <li>Hej, <?php echo $username;?>!</li>
        <li id="li_change_password"><a href="<?php echo $password_url;?>">Change password</a></li>
      </ul>
    </div>
    <div class="group">
      <h2>Your Activities</h2>
      <ul>
        <?php
          foreach ($user_activities as $act) {
              echo '<li id="li_disc">' . $act->event . " @ " . $act->stamp . "</li>";
          }
        ?>
      </ul>
    </div>
    <div class="group">
      <h2>System Runtime Info</h2>
        <ul>
          <li>Version: <?php echo $this_system_version;?></li>
          <li>Web server: <?php echo $web_server;?></li>
          <li>Load: <?php echo "$sys_load[0], $sys_load[1], $sys_load[2]"; ?></li>
          <li>Contact admin: <?php echo $admin_email;?></li>
          <li><a href="https://github.com/vvoody/anm-host-management">Source Code</a></li>
        </ul>
    </div>
  </div>

<br class="clearfloat" />
<br class="clearfloat" />
</div>
