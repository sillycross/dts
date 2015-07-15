<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>想购买什么物品？<br><br>
<input type="hidden" name="mode" value="shop">
<input type="hidden" name="shoptype" value="<?php } else { echo '___aadh'; } ?><?php echo $shop?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" onmouseover="status=' ';return true;">离开商店</a><br>
<input type="radio" name="command" id="shop" value="shop"><a onclick=sl('shop'); href="javascript:void(0);" onmouseover="status=' ';return true;">返回列表</a><br><br>
<?php } else { echo '___aadi'; } ?><?php if(is_array($itemdata)) { foreach($itemdata as $idata) { if($idata['sid']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><a onclick=sl("<?php } else { echo '___aadj'; } ?><?php echo $idata['sid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"); href="javascript:void(0);" onmouseover="status=' ';return true;"><input type="radio" name="command" id="<?php } else { echo '___aadk'; } ?><?php echo $idata['sid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" value="<?php } else { echo '___aadl'; } ?><?php echo $idata['sid']?>"><?php echo $idata['item']?>/<?php echo $idata['itmk_words']?>/<?php echo $idata['itme']?>/<?php echo $idata['itms']?>
<?php if($idata['itmsk_words']) { ?>
/<?php echo $idata['itmsk_words']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> 【价:<?php } else { echo '___aadm'; } ?><?php echo $idata['price']?>,数:<?php echo $idata['num']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>】</a><br>
<?php } else { echo '___aadn'; } ?><?php } } } if($shop==1||$shop==2||$shop==6||$shop==7||$shop==8||$shop==10||$shop==11||$shop==12||$shop==13) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>请输入购买数量：<input type="text" name="buynum" value="1" size="5" maxlength="5">
<?php } else { echo '___aado'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="buynum" value="1">
<?php } else { echo '___aadp'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><br>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aadq'; } ?>