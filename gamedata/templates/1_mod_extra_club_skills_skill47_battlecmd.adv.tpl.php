<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill47\get_rage_cost47(); if($rage>=$rageneed) { if(substr($wepk,0,2) == 'WC') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="花雨" 
onclick="$('command').value='<?php } else { echo '___aahS'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=47;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
本次攻击随机附加一种基本属性伤害；消耗<span class="red"><?php } else { echo '___aahT'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafQ'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="花雨" disabled="true"><br />
<div style="width:90%; text-align:left;">
手持<span class="yellow">投系武器</span>方可发动
</div>
<?php } else { echo '___aahU'; } ?><?php } } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="花雨" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aahV'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafS'; } ?><?php } ?>

