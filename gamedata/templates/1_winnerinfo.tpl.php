<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table align="center">
<tr><td>
<span id="main">
<?php } else { echo '___aamB'; } ?><?php include template('profile'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
</td>
<td>
<table border="1" width="250" height="300" cellspacing="0" cellpadding="0" >
<tr height="1">
<td height="20" class="b1"><span><span class="yellow">系统状况</span></td>
</tr>
<tr><td valign="top"class="b3" style="text-align: left"><div>
第 <?php } else { echo '___aamC'; } ?><?php echo $gid?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 回生存游戏<br>
参加人数： <?php } else { echo '___aamD'; } ?><?php echo $vnum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 人<br>
胜利方式： <?php } else { echo '___aamE'; } ?><?php echo $gwin[$wmode]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
游戏进行时间：<?php } else { echo '___aamF'; } ?><?php echo $gdate?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
游戏开始时间：<?php } else { echo '___aamG'; } ?><?php echo $gsdate?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>           
游戏结束时间：<?php } else { echo '___aamH'; } ?><?php echo $gedate?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
本场最高伤害者： <?php } else { echo '___aamI'; } ?><?php echo $hdp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> (<?php } else { echo '___aamJ'; } ?><?php echo $hdmg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>)<br>
本场最多杀人者： <?php } else { echo '___aamK'; } ?><?php echo $hkp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> (<?php } else { echo '___aamJ'; } ?><?php echo $hkill?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>)<br>
<br>
<form method="post" name="back" action="winner.php">
<input type="submit" name="submit" value="返回">
</form>

</div></td>
</tr>
</table>
</td>
</tr>
</table>
<?php } else { echo '___aamL'; } ?>