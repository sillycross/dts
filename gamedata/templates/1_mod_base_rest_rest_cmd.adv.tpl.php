<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_REST__VARS__rest_sleep_time,$___LOCAL_REST__VARS__rest_heal_time,$___LOCAL_REST__VARS__rest_recover_time,$___LOCAL_REST__VARS__restinfo,$___LOCAL_REST__VARS__rest_hospital_list; $rest_sleep_time=&$___LOCAL_REST__VARS__rest_sleep_time; $rest_heal_time=&$___LOCAL_REST__VARS__rest_heal_time; $rest_recover_time=&$___LOCAL_REST__VARS__rest_recover_time; $restinfo=&$___LOCAL_REST__VARS__restinfo; $rest_hospital_list=&$___LOCAL_REST__VARS__rest_hospital_list;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="rest1" name="rest1" value="睡眠" onclick="$('command').value='rest1';postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" id="rest2" name="rest2" value="治疗" onclick="$('command').value='rest2';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aagW'; } ?><?php if(in_array($pls,$rest_hospital_list)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="rest3" name="rest3" value="静养" onclick="$('command').value='rest3';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aagX'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaaX'; } ?>