<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="gameinfomng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gameinfomng">
<input type="hidden" id="command" name="command" value="">

<table class="admin">
<tr>
<th>游戏变量</th>
<th>当前数据</th>
<th>操作</th>
</tr>

<tr>
<td>游戏局数</td>
<td><?php } else { echo '___aas-'; } ?><?php echo $gamenum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td></td>
</tr>
<tr>
<td>游戏状态</td>
<td><?php } else { echo '___aata'; } ?><?php echo $gstate[$gamestate]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>
<?php } else { echo '___aatb'; } ?><?php if($gamestate == 0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="准备开始" onclick="$('command').value='gsedit_10'">
<?php } else { echo '___aatc'; } ?><?php } elseif($gamestate == 10) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="开始游戏" onclick="$('command').value='gsedit_20'"><br>
<input type="submit" value="结束游戏" onclick="$('command').value='gsedit_0'">
<?php } else { echo '___aatd'; } ?><?php } elseif($gamestate == 20) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="停止激活" onclick="$('command').value='gsedit_30'"><br>
<input type="submit" value="进入连斗" onclick="$('command').value='gsedit_40'"><br>
<input type="submit" value="进入死斗" onclick="$('command').value='gsedit_50'"><br>
<input type="submit" value="结束游戏" onclick="$('command').value='gsedit_0'">
<?php } else { echo '___aate'; } ?><?php } elseif($gamestate == 30) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="进入连斗" onclick="$('command').value='gsedit_40'"><br>
<input type="submit" value="进入死斗" onclick="$('command').value='gsedit_50'"><br>
<input type="submit" value="结束游戏" onclick="$('command').value='gsedit_0'">
<?php } else { echo '___aatf'; } ?><?php } elseif($gamestate == 40) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="进入死斗" onclick="$('command').value='gsedit_50'"><br>
<input type="submit" value="结束游戏" onclick="$('command').value='gsedit_0'">
<?php } else { echo '___aatg'; } ?><?php } elseif($gamestate == 50) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="结束游戏" onclick="$('command').value='gsedit_0'">
<?php } else { echo '___aath'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td>
<?php } else { echo '___aati'; } ?><?php if($gamestate) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>当前游戏开始时间
<?php } else { echo '___aatj'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>下局游戏开始时间
<?php } else { echo '___aatk'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>                
<input type="text" name="setyear" size="4" value="<?php } else { echo '___aatl'; } ?><?php echo $styear?>"><?php echo $lang['year']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="setmonth" size="2" value="<?php } else { echo '___aatm'; } ?><?php echo $stmonth?>"><?php echo $lang['month']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="setday" size="2" value="<?php } else { echo '___aatn'; } ?><?php echo $stday?>"><?php echo $lang['day']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="sethour" size="2" value="<?php } else { echo '___aato'; } ?><?php echo $sthour?>"><?php echo $lang['hour']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="setmin" size="2" value="<?php } else { echo '___aatp'; } ?><?php echo $stmin?>"><?php echo $lang['min']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>
<?php } else { echo '___aatb'; } ?><?php if($gamestate) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="无法设定" disabled>
<?php } else { echo '___aatq'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="submit" value="设定时间" onclick="$('command').value='sttimeedit'">
<?php } else { echo '___aatr'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td>当前天气</td>
<td>
<select name="iweather">
<?php } else { echo '___aats'; } ?><?php if(is_array($wthinfo)) { foreach($wthinfo as $n => $wth) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="<?php } else { echo '___aaiU'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" 
<?php } else { echo '___aatt'; } ?><?php if($weather == $n) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $wth?>
<?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</td>
<td><input type="submit" value="更改天气" onclick="$('command').value='wthedit'"></td>
</tr>
<tr>
<td>禁区列表</td>
<td><?php } else { echo '___aatu'; } ?><?php echo $arealiststr?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td></td>
</tr>
<tr>
<td>下次禁区列表</td>
<td><?php } else { echo '___aatv'; } ?><?php echo $nextarealiststr?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td></td>
</tr>
<tr>
<td>已有禁区数目</td>
<td><?php } else { echo '___aatw'; } ?><?php echo $areanum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td></td>
</tr>
<tr>
<td>下次禁区时间</td>
<td>
<input type="text" name="areayear" size="4" disabled value="<?php } else { echo '___aatx'; } ?><?php echo $aryear?>"><?php echo $lang['year']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="areamonth" size="2" disabled value="<?php } else { echo '___aaty'; } ?><?php echo $armonth?>"><?php echo $lang['month']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="areaday" size="2" disabled value="<?php } else { echo '___aatz'; } ?><?php echo $arday?>"><?php echo $lang['day']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="areahour" size="2" disabled value="<?php } else { echo '___aatA'; } ?><?php echo $arhour?>"><?php echo $lang['hour']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="text" name="areamin" size="2" disabled value="<?php } else { echo '___aatB'; } ?><?php echo $armin?>"><?php echo $lang['min']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="submit" value="立刻禁区" onclick="$('command').value='areaadd'"></td>
</tr>
<tr>
<td>禁区解除</td>
<td>
<input type="radio" name="ihack" value="1" 
<?php } else { echo '___aatC'; } ?><?php if($hack) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked
<?php } else { echo '___aadN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>是&nbsp;&nbsp;&nbsp;<input type="radio" name="ihack" value="0" 
<?php } else { echo '___aatD'; } ?><?php if(!$hack) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked
<?php } else { echo '___aadN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>否
</td>
<td><input type="submit" value="更改状态" onclick="$('command').value='hackedit'"></td>
</tr>
</table>
</form> <?php } else { echo '___aatE'; } ?>