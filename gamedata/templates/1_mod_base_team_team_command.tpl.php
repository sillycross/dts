<?php if(!defined('IN_GAME')) exit('Access Denied'); if((\team\check_team_button_exist())) { if(!$teamID) { ?>
<input type="button" class="cmdbutton" id="teammake" name="teammake" value="组建队伍" onclick="$('command').value='team';$('subcmd').name='teamcmd';$('subcmd').value='teammake';postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" id="teammake" name="teamjoin" value="加入队伍" onclick="$('command').value='team';$('subcmd').name='teamcmd';$('subcmd').value='teamjoin';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { ?>
<input type="button" class="cmdbutton" id="teammake" name="teamquit" value="脱离队伍" onclick="$('command').value='team';$('subcmd').name='teamcmd';$('subcmd').value='teamquit';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } ?>
<br />
<?php } ?>
