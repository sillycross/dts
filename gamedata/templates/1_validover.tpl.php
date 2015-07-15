<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div class="subtitle" >入场表格填写完成</div>
<p><img border="0" src="img/story_0.gif" style="align:center"></p>
<table border="0" cellspacing="0" align="center">
  <tbody>
<tr>
<td class="b1"><span>姓名</span></td>
<td class="b3"><span><?php } else { echo '___aasI'; } ?><?php echo $cuser?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td rowspan="3" colspan="2" class="b3"><span><img src="./img/<?php } else { echo '___aasJ'; } ?><?php echo $gd?>_<?php echo $icon?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>.gif" border="0" /></span></td>
</tr>
<tr>
<td class="b1"><span>学号</span></td>
<td class="b3"><span><?php } else { echo '___aasK'; } ?><?php echo $sexinfo[$gd]?><?php echo $sNo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>号</span></td>

</tr>
<tr>
<td class="b1"><span>内定称号</span></td>
<td class="b3"><span>无</span></td>
</tr>
<tr>
<td class="b1"><span>生命</span></td>
<td class="b3"><span><?php } else { echo '___aasL'; } ?><?php echo $hp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacQ'; } ?><?php echo $mhp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b1"><span>体力</span></td>
<td class="b3"><span><?php } else { echo '___aasM'; } ?><?php echo $sp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacQ'; } ?><?php echo $msp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b1"><span>攻击力</span></td>
<td class="b3"><span><?php } else { echo '___aasN'; } ?><?php echo $att?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b1"><span>防御力</span></td>
<td class="b3"><span><?php } else { echo '___aasO'; } ?><?php echo $def?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b1"><span>武器</span></td>
<td class="b3" colspan="3"><span><?php } else { echo '___aasP'; } ?><?php echo $wep?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b1"><span>随机道具1</span></td>
<td class="b3" colspan="3"><span><?php } else { echo '___aasQ'; } ?><?php echo $itm['3']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b1"><span>随机道具2</span></td>
<td class="b3" colspan="3"><span><?php } else { echo '___aasR'; } ?><?php echo $itm['4']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
  </tbody>
</table>
<p align="center">“<?php } else { echo '___aasS'; } ?><?php echo $cuser?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>，对吧？正在为您创建虚拟身份……<br>
“创建完成！您可以凭这个身份参加我们的特别活动了。
<br>
“会场入口就在前面。动漫祭的开幕仪式就要开始了，请您尽快入场。”<br><br>

<form method="post"  action="valid.php" style="margin: 0px">
<input type="hidden" name="mode" value="notice">
<input type="submit" name="enter" value="进入会场">
</form>
</p>
<?php } else { echo '___aasT'; } ?><?php include template('footer'); ?>

