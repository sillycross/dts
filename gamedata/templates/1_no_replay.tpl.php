<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); \bubblebox\bubblebox_set_style('id:replayhint;height:200;width:500;cancellable:0;'); include template('MOD_BUBBLEBOX_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table height=198px width=100%><tr><td align="center" valign="middle">
<span><font size="50px">录像文件不存在</font><br><br>
可能是由于录像功能当时还未开放，或出现了一个系统错误<br><br>
<a href="#" onclick="if (history.length>1) javascript:history.back(-1); else window.location.href='index.php';">请点击这里返回主页</a></span>
</td></tr></table>
<?php } else { echo '___aasZ'; } ?><?php include template('MOD_BUBBLEBOX_END'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript">bubblebox_show('replayhint');</script>
<?php } else { echo '___aas0'; } ?><?php include template('game_interface'); include template('footer'); ?>

