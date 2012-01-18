<div id="maincontainer">
  <div id="main_pane_left">
    <div class="group">
      <h2>General Settings</h2>
      <ul>
        <li>blablabla</li>
      </ul>
    </div>
    <div class="group">
      <h2>Appearance Settings</h2>
      <ul>
        <li>
          <h3>You have following alarms in the recent week:</h2>
          <table border="0" width="30%" class="norm" cellpadding="0" cellspacing="0">
          <tr>
            <td>NOTICE</td>
            <td><?php echo anchor('stats/index/notice', $notices, 'style="text-decoration:underline;"');?></td>
          </tr>
          <tr>
            <td>WARNING</td>
            <td><?php echo anchor('stats/index/warning', $warnings, 'style="text-decoration:underline;"');?></td>
          </tr>
          <tr>
            <td>ERROR</td>
            <td><?php echo anchor('stats/index/error', $errors, 'style="text-decoration:underline;"');?></td>
          </tr>
          <tr>
            <td>CRITICAL</td>
            <td><?php echo anchor('stats/index/critical',$criticals, 'style="text-decoration:underline;"');?></td>
          </tr>
          </table>
        </li>
      </ul>
    </div>
  </div>
  <div id="main_pane_right">
    <div class="group">
      <h2>Personal Setting</h2>
      <ul>
        <li>Hej, xxxx!</li>
        <li id="li_change_password"><a href="#">Change password</a></li>
      </ul>
    </div>
    <div class="group">
      <h2>Web server</h2>
      <ul>
        <li>Apache/2.2.21 (Unix) DAV/2 PHP/5.3.8</li>
        <li>MySQL client version: mysqlnd 5.0.8-dev - 20102224 - $Revision: 310735 $</li>
      </ul>
    </div>
    <div class="group">
      <h2>System Runtime Info</h2>
        <ul>
          <li>Version: 1.0</li>
          <li>Web server: Apache</li>
          <li>Web server: Apache</li>
          <li><a href="https://github.com/vvoody/anm-host-management">Source Code</a></li>
        </ul>
    </div>
  </div>

<br class="clearfloat" />
<br class="clearfloat" />
</div>
