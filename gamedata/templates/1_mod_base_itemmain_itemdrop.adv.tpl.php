<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>你想丢掉什么？<br><br>
<input type="hidden" name="mode" value="itemmain">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<?php } else { echo '___aabo'; } ?><?php if($weps && $wepe) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="wep" value="dropwep"><a onclick=sl('wep'); href="javascript:void(0);" ><?php } else { echo '___aabp'; } ?><?php echo $wep?>/<?php echo $wepe?>/<?php echo $weps?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($arbs && $arbe) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="arb" value="droparb"><a onclick=sl('arb'); href="javascript:void(0);" ><?php } else { echo '___aabr'; } ?><?php echo $arb?>/<?php echo $arbe?>/<?php echo $arbs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($arhs) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="arh" value="droparh"><a onclick=sl('arh'); href="javascript:void(0);" ><?php } else { echo '___aabs'; } ?><?php echo $arh?>/<?php echo $arhe?>/<?php echo $arhs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($aras) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="ara" value="dropara"><a onclick=sl('ara'); href="javascript:void(0);" ><?php } else { echo '___aabt'; } ?><?php echo $ara?>/<?php echo $arae?>/<?php echo $aras?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($arfs) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="arf" value="droparf"><a onclick=sl('arf'); href="javascript:void(0);" ><?php } else { echo '___aabu'; } ?><?php echo $arf?>/<?php echo $arfe?>/<?php echo $arfs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($arts) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="art" value="dropart"><a onclick=sl('art'); href="javascript:void(0);" ><?php } else { echo '___aabv'; } ?><?php echo $art?>/<?php echo $arte?>/<?php echo $arts?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="itm1" value="dropitm1"><a onclick=sl('itm1'); href="javascript:void(0);" ><?php } else { echo '___aabw'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="itm2" value="dropitm2"><a onclick=sl('itm2'); href="javascript:void(0);" ><?php } else { echo '___aabx'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="itm3" value="dropitm3"><a onclick=sl('itm3'); href="javascript:void(0);" ><?php } else { echo '___aaby'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="itm4" value="dropitm4"><a onclick=sl('itm4'); href="javascript:void(0);" ><?php } else { echo '___aabz'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="itm5" value="dropitm5"><a onclick=sl('itm5'); href="javascript:void(0);" ><?php } else { echo '___aabA'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="itm6" value="dropitm6"><a onclick=sl('itm6'); href="javascript:void(0);" ><?php } else { echo '___aabB'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><br>
<input type="button" class="cmdbutton" name="submit" value="确定并丢弃" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aabC'; } ?>