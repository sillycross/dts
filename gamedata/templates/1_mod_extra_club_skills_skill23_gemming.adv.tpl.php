<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="special">
<input type="hidden" id="command" name="command" value="skill23_special">
<input type="hidden" id="subcmd" name="subcmd" value="gemming">
宝石骑士称号可以将各类<span class="yellow">宝石</span>或<span class="yellow">方块</span>镶嵌在你的<span class="yellow">武器</span>或<span class="yellow">防具</span>上，为其添加不同的<span class="yellow">防御属性</span>或<span class="yellow">攻击属性</span>。<br>
各种宝石/方块的效果列表请参见帮助。<br><br>
请选择你将镶嵌的武器或防具：<br>
<select id="skill23_t1" name="skill23_t1" value="back">
<?php } else { echo '___aadD'; } ?><?php if(($weps && $wepk!='WN')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="wep"><span class="yellow"><?php } else { echo '___aadE'; } ?><?php echo $wep?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $wepe?>/<?php echo $weps?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if(($arbs)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="arb"><span class="yellow"><?php } else { echo '___aadH'; } ?><?php echo $arb?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $arbe?>/<?php echo $arbs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if(($arhs)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="arh"><span class="yellow"><?php } else { echo '___aadI'; } ?><?php echo $arh?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $arhe?>/<?php echo $arhs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if(($aras)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="ara"><span class="yellow"><?php } else { echo '___aadJ'; } ?><?php echo $ara?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $arae?>/<?php echo $aras?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if(($arfs)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="arf"><span class="yellow"><?php } else { echo '___aadK'; } ?><?php echo $arf?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $arfe?>/<?php echo $arfs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br><br>
请选择用于镶嵌的宝石或方块。<br> 
<select id="skill23_t2" name="skill23_t2" value="back">
<option value="back">返回<br>
<?php } else { echo '___aadL'; } ?><?php if($itms1 && strpos($itm1,'方块')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1"><span class="yellow"><?php } else { echo '___aadM'; } ?><?php echo $itm1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if($itms2 && strpos($itm2,'方块')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2"><span class="yellow"><?php } else { echo '___aadN'; } ?><?php echo $itm2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if($itms3 && strpos($itm3,'方块')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3"><span class="yellow"><?php } else { echo '___aadO'; } ?><?php echo $itm3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if($itms4 && strpos($itm4,'方块')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4"><span class="yellow"><?php } else { echo '___aadP'; } ?><?php echo $itm4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if($itms5 && strpos($itm5,'方块')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5"><span class="yellow"><?php } else { echo '___aadQ'; } ?><?php echo $itm5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } if($itms6 && strpos($itm6,'方块')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6"><span class="yellow"><?php } else { echo '___aadR'; } ?><?php echo $itm6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<?php } else { echo '___aadF'; } ?><?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<?php } else { echo '___aadG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br><br>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" name="submit" value="返回" onclick="$('command').value='menu'; postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aadS'; } ?>