<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" id="mode" name="mode" value="command">
<input type="hidden" id="command" name="command" value="menu">
<input type="hidden" id="subcmd" name="subcmd" value="">
现在想要做什么？<br /><br />
<?php } else { echo '___aar-'; } ?><?php if((defined('MOD_EXPLORE'))) { include template('MOD_EXPLORE_EXPLORE'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaaX'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" 
<?php } else { echo '___aasa'; } ?><?php if(strpos($itmk1,'W')===0 || strpos($itmk1,'D')===0 || strpos($itmk1,'A')===0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="装备"
<?php } else { echo '___aasb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="使用"
<?php } else { echo '___aasc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('mode').value='command';$('command').value='itm1';postCmd('gamecmd','command.php');this.disabled=true;"><span class="yellow"><?php } else { echo '___aasd'; } ?><?php echo $itm1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aady'; } ?><?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" 
<?php } else { echo '___aasa'; } ?><?php if(strpos($itmk2,'W')===0 || strpos($itmk2,'D')===0 || strpos($itmk2,'A')===0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="装备"
<?php } else { echo '___aasb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="使用"
<?php } else { echo '___aasc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('mode').value='command';$('command').value='itm2';postCmd('gamecmd','command.php');this.disabled=true;"><span class="yellow"><?php } else { echo '___aase'; } ?><?php echo $itm2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aady'; } ?><?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" 
<?php } else { echo '___aasa'; } ?><?php if(strpos($itmk3,'W')===0 || strpos($itmk3,'D')===0 || strpos($itmk3,'A')===0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="装备"
<?php } else { echo '___aasb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="使用"
<?php } else { echo '___aasc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('mode').value='command';$('command').value='itm3';postCmd('gamecmd','command.php');this.disabled=true;"><span class="yellow"><?php } else { echo '___aasf'; } ?><?php echo $itm3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aady'; } ?><?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" 
<?php } else { echo '___aasa'; } ?><?php if(strpos($itmk4,'W')===0 || strpos($itmk4,'D')===0 || strpos($itmk4,'A')===0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="装备"
<?php } else { echo '___aasb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="使用"
<?php } else { echo '___aasc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('mode').value='command';$('command').value='itm4';postCmd('gamecmd','command.php');this.disabled=true;"><span class="yellow"><?php } else { echo '___aasg'; } ?><?php echo $itm4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aady'; } ?><?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" 
<?php } else { echo '___aasa'; } ?><?php if(strpos($itmk5,'W')===0 || strpos($itmk5,'D')===0 || strpos($itmk5,'A')===0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="装备"
<?php } else { echo '___aasb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="使用"
<?php } else { echo '___aasc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('mode').value='command';$('command').value='itm5';postCmd('gamecmd','command.php');this.disabled=true;"><span class="yellow"><?php } else { echo '___aash'; } ?><?php echo $itm5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aady'; } ?><?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" 
<?php } else { echo '___aasa'; } ?><?php if(strpos($itmk6,'W')===0 || strpos($itmk6,'D')===0 || strpos($itmk6,'A')===0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="装备"
<?php } else { echo '___aasb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>value="使用"
<?php } else { echo '___aasc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('mode').value='command';$('command').value='itm6';postCmd('gamecmd','command.php');this.disabled=true;"><span class="yellow"><?php } else { echo '___aasi'; } ?><?php echo $itm6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aady'; } ?><?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php if(defined('MOD_ITEMMIX')) { include template('MOD_ITEMMIX_ITEMMIX_CMD'); } if(defined('MOD_ITEMMAIN')) { include template('MOD_ITEMMAIN_ITMCOMMAND'); } if(defined('MOD_REST')) { include template('MOD_REST_REST_CMD'); } if(defined('MOD_TEAM')) { include template('MOD_TEAM_TEAM_COMMAND'); } if(defined('MOD_ITEMSHOP')) { include template('MOD_ITEMSHOP_ITEMSHOP_CMD'); } if(defined('MOD_CLUBBASE')) { include template('MOD_CLUBBASE_SKILLPAGE_PROFILE'); } if(defined('MOD_WEPCHANGE')) { include template('MOD_WEPCHANGE_WEPCHANGE_PROFILE'); } if(defined('MOD_SONG')) { include template('MOD_SONG_SONG_PROFILE'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php if(defined('MOD_CLUBBASE')) { include template('MOD_CLUBBASE_PROFILESKILLPAGE'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadz'; } ?><?php if($club == 7) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="sp_adtsk" name="sp_adtsk" value="武器带电" onclick="$('command').value='special';$('subcmd').name='sp_cmd';$('subcmd').value='sp_adtsk';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aasj'; } ?><?php } elseif($club == 8) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="sp_adtsk" name="sp_adtsk" value="武器淬毒" onclick="$('command').value='special';$('subcmd').name='sp_cmd';$('subcmd').value='sp_adtsk';postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" id="sp_poison" name="sp_poison" value="检查毒物" onclick="$('command').value='special';$('subcmd').name='sp_cmd';$('subcmd').value='sp_poison';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aask'; } ?><?php } ?>

