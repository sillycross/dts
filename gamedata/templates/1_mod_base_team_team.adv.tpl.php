<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="team">
<input type="hidden" name="command" value="<?php } else { echo '___aaet'; } ?><?php echo $teamcmd?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<div>队伍名称 : <input size="15" type="text" name="nteamID" maxlength="30"></div>
<div>15个字以内。<div>
<div>队伍密码 : <input size="15" type="text" name="nteamPass" maxlength="30"></div>
<div>15个字以内。<div>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aaeu'; } ?>