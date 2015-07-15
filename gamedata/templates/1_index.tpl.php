<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table align="center" style="text-align:center;border:0;padding:0;">
<tr>
<td><span class="yellow">游戏版本：</span></td>
<td style="text-align:left;"><span class="evergreen"><?php } else { echo '___aana'; } ?><?php echo $gameversion?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td style="text-align:center;padding:0 0 0 25px;"><span class="yellow">站长留言</span></td>
</tr>
<tr>
<td><span class="yellow">当前时刻：</span></td>
<td style="text-align:left;"><span class="evergreen"><?php } else { echo '___aanb'; } ?><?php echo $month?>月<?php echo $day?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>日 星期<?php } else { echo '___aanc'; } ?><?php echo $week["$wday"]?> <?php echo $hour?>:<?php echo $min?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td rowspan="4" style="width:400px;vertical-align:top;text-align:left;padding:0 0 0 25px;"><span class="evergreen"><?php } else { echo '___aand'; } ?><?php echo $adminmsg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td><span class="yellow">系统状况：</span></td>
<td style="text-align:left;"><span class="evergreen"><?php } else { echo '___aane'; } ?><?php echo $systemmsg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td><span class="yellow">游戏情报：</span></td>
<td style="text-align:left;"><span class="evergreen"><span class="evergreen2">第 <?php } else { echo '___aanf'; } ?><?php echo $gamenum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 回游戏 <?php } else { echo '___aang'; } ?><?php echo $gstate[$gamestate]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></span></td>
</tr>
<tr>
<td colspan="2" style="vertical-align:top;">
<div>
<?php } else { echo '___aanh'; } ?><?php if($gamestate > 10 ) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>本局游戏已经进行  <span id="timing"></span><script type="text/javascript">updateTime(<?php } else { echo '___aani'; } ?><?php echo $timing?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>,1);</script><br> 
<?php } else { echo '___aanj'; } ?><?php if($hplayer) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>当前最高伤害 <?php } else { echo '___aank'; } ?><?php echo $hplayer?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> (<?php } else { echo '___aamQ'; } ?><?php echo $hdamage?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>)<br>
<?php } else { echo '___aanl'; } ?><?php } } else { if($starttime > $now) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>下局游戏开始于  <span id="timing"></span><script type="text/javascript">updateTime(<?php } else { echo '___aanm'; } ?><?php echo $timing?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>,0);</script>   后<br>
<?php } else { echo '___aann'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>下局游戏开始时间  <span id="timing"></span>未定<br>
<?php } else { echo '___aano'; } ?><?php } if($hplayer) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>上局最高伤害 <?php } else { echo '___aanp'; } ?><?php echo $hplayer?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> (<?php } else { echo '___aamQ'; } ?><?php echo $hdamage?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>)<br>
<?php } else { echo '___aanl'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<div>
上局结果：<span id="lastwin"><?php } else { echo '___aanq'; } ?><?php echo $gwin[$winmode]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php if($winner) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>，优胜者：<span id="lastwinner"><?php } else { echo '___aanr'; } ?><?php echo $winner?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>

禁区间隔时间： <?php } else { echo '___aans'; } ?><?php echo $areahour?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 分钟 ， <?php } else { echo '___aant'; } ?><?php echo $arealimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 禁后停止激活<br>
每次增加禁区： <?php } else { echo '___aanu'; } ?><?php echo $areaadd?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 个 ， 当前禁区数： <?php } else { echo '___aanv'; } ?><?php echo $areanum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
自动逃避禁区功能：
<?php } else { echo '___aanw'; } ?><?php if($areaesc && $gamestate < 40) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">开放</span>
<?php } else { echo '___aanx'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">关闭</span>
<?php } else { echo '___aany'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<span class="red">注意：进入连斗后，自动逃避功能自动关闭。</span><br><br>


激活人数：<span id="alivenum"><?php } else { echo '___aanz'; } ?><?php echo $validnum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
生存人数：<span id="alivenum"><?php } else { echo '___aanA'; } ?><?php echo $alivenum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
死亡总数：<span id="alivenum"><?php } else { echo '___aanB'; } ?><?php echo $deathnum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<br />
<?php } else { echo '___aanC'; } ?><?php if($cuser) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />欢迎你，<?php } else { echo '___aanD'; } ?><?php echo $cuser?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>！
<form method="post" name="togame" action="game.php">
<input type="hidden" name="mode" value="main">
<input type="submit" name="enter" value="进入游戏">
</form>

<form method="post" name="quitgame" action="game.php">
<input type="hidden" name="mode" value="quit">
<input type="submit" name="quit" value="账号退出">
</form>
<?php } else { echo '___aanE'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="login" action="login.php">
<input type="hidden" name="mode" value="main">
账号<input type="text" name="username" size="20" maxlength="20" value="<?php } else { echo '___aanF'; } ?><?php echo $cuser?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
密码<input type="password" name="password" size="20" maxlength="20" value="<?php } else { echo '___aanG'; } ?><?php echo $cpass?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="submit" name="enter" value="登录">
</form>
<?php } else { echo '___aanH'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="evergreen2">第一次玩的，请先看 <a href="help.php" class="clit">游戏帮助</a> !!!</span><br>
</td>
</tr>
</table>
<?php } else { echo '___aanI'; } ?><?php include template('footer'); ?>

