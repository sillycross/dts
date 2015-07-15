<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>想购买什么物品？<br><br>
<input type="hidden" name="mode" value="special">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" onmouseover="status=' ';return true;">离开商店</a><br><br>
<input type="radio" name="command" id="shop1" value="shop1"><a onclick=sl('shop1'); href="javascript:void(0);" onmouseover="status=' ';return true;">【补给品】</a><br>
<input type="radio" name="command" id="shop2" value="shop2"><a onclick=sl('shop2'); href="javascript:void(0);" onmouseover="status=' ';return true;">【药剂】</a><br>
<input type="radio" name="command" id="shop3" value="shop3"><a onclick=sl('shop3'); href="javascript:void(0);" onmouseover="status=' ';return true;">【<?php } else { echo '___aac5'; } ?><?php echo $iteminfo['WP']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>】</a><br>
<input type="radio" name="command" id="shop4" value="shop4"><a onclick=sl('shop4'); href="javascript:void(0);" onmouseover="status=' ';return true;">【<?php } else { echo '___aac6'; } ?><?php echo $iteminfo['WK']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>】</a><br>
<input type="radio" name="command" id="shop5" value="shop5"><a onclick=sl('shop5'); href="javascript:void(0);" onmouseover="status=' ';return true;">【<?php } else { echo '___aac7'; } ?><?php echo $iteminfo['WG']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>】</a><br>
<input type="radio" name="command" id="shop6" value="shop6"><a onclick=sl('shop6'); href="javascript:void(0);" onmouseover="status=' ';return true;">【<?php } else { echo '___aac8'; } ?><?php echo $iteminfo['WC']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>】</a><br>
<input type="radio" name="command" id="shop7" value="shop7"><a onclick=sl('shop7'); href="javascript:void(0);" onmouseover="status=' ';return true;">【<?php } else { echo '___aac9'; } ?><?php echo $iteminfo['WD']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>】</a><br>
<input type="radio" name="command" id="shop8" value="shop8"><a onclick=sl('shop8'); href="javascript:void(0);" onmouseover="status=' ';return true;">【<?php } else { echo '___aac.'; } ?><?php echo $iteminfo['WF']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>材料】</a><br>
<input type="radio" name="command" id="shop9" value="shop9"><a onclick=sl('shop9'); href="javascript:void(0);" onmouseover="status=' ';return true;">【防具】</a><br>
<input type="radio" name="command" id="shop10" value="shop10"><a onclick=sl('shop10'); href="javascript:void(0);" onmouseover="status=' ';return true;">【书籍】</a><br>
<input type="radio" name="command" id="shop11" value="shop11"><a onclick=sl('shop11'); href="javascript:void(0);" onmouseover="status=' ';return true;">【电子装备】</a><br>
<input type="radio" name="command" id="shop12" value="shop12"><a onclick=sl('shop12'); href="javascript:void(0);" onmouseover="status=' ';return true;">【杂物】</a><br>
<input type="radio" name="command" id="shop13" value="shop13"><a onclick=sl('shop13'); href="javascript:void(0);" onmouseover="status=' ';return true;"><font style="background:url(http://dts.acfun.tv/img/backround3.gif) repeat-x">【埃克法轻工特供武器】</font></a><br>
<input type="radio" name="command" id="shop14" value="shop14"><a onclick=sl('shop14'); href="javascript:void(0);" onmouseover="status=' ';return true;"><font style="background:url(http://dts.acfun.tv/img/backround3.gif) repeat-x">【林苍月的提示】</font></a><br>
<input type="radio" name="command" id="shop15" value="shop15"><a onclick=sl('shop15'); href="javascript:void(0);" onmouseover="status=' ';return true;"><font style="background:url(http://dts.acfun.tv/img/backround3.gif) repeat-x">【Key社纪念品专卖】</font></a><br>
<br><br>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aac-'; } ?>