<?php if(!defined('IN_GAME')) exit('Access Denied'); if($urcmd == 'list') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="urpage" onsubmit="admin.php">
<input type="hidden" name="mode" value="urlist">
<input type="hidden" id="urcmd" name="urcmd" value="list">
<?php } else { echo '___aapc'; } ?><?php if($pagecmd=='check') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="pagecmd" value="check">
<input type="hidden" name="urorder" value="<?php } else { echo '___aapd'; } ?><?php echo $urorder?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="urorder2" value="<?php } else { echo '___aape'; } ?><?php echo $urorder2?>">
<?php } elseif($pagecmd=='find') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="pagecmd" value="find">
<input type="hidden" name="checkinfo" value="<?php } else { echo '___aapf'; } ?><?php echo $checkinfo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="checkmode" value="<?php } else { echo '___aakI'; } ?><?php echo $checkmode?>">
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="start" value="<?php } else { echo '___aapg'; } ?><?php echo $start?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" id="pagemode" name="pagemode" value="">
<input type="submit" value="上一页" onclick="$('pagemode').value='up';">
<span class="yellow"><?php } else { echo '___aaph'; } ?><?php echo $resultinfo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<input type="submit" value="下一页" onclick="$('pagemode').value='down';">
<?php } else { echo '___aapi'; } ?><?php if($urdata) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table class="admin">
<tr>
<th>选</th>
<th>账号</th>
<th>密码</th>
<th>权限</th>
<th>最新游戏</th>
<th>ip</th>
<th>分数</th>
<th>性别</th>
<th>头像</th>
<th>社团</th>
<th>口头禅</th>
<th>杀人留言</th>
<th>遗言</th>
<th>操作</th>
</tr>
<?php } else { echo '___aapj'; } ?><?php if($urdata) { if(is_array($urdata)) { foreach($urdata as $n => $ur) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<?php } else { echo '___aapk'; } ?><?php if($ur['groupid']>=$mygroup) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td><input type="checkbox" id="user_<?php } else { echo '___aapl'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" name="user_<?php } else { echo '___aapm'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" value="<?php } else { echo '___aade'; } ?><?php echo $ur['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" disabled="true"></td>
<td><?php } else { echo '___aapn'; } ?><?php echo $ur['username']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="pass_<?php } else { echo '___aapo'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="20" value="无法修改" disabled="true"></td>
<td><?php } else { echo '___aapp'; } ?><?php echo $urgroup[$ur['groupid']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>第<?php } else { echo '___aapq'; } ?><?php echo $ur['lastgame']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>局</td>
<td><?php } else { echo '___aapr'; } ?><?php echo $ur['ip']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $ur['credits']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>
<select name="gender_<?php } else { echo '___aaps'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" disabled="true">
<option value="0" 
<?php } else { echo '___aapt'; } ?><?php if($ur['gender']==0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $ursex['0']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="m" 
<?php } else { echo '___aapu'; } ?><?php if($ur['gender']=='m') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $ursex['m']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="f" 
<?php } else { echo '___aapv'; } ?><?php if($ur['gender']=='f') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $ursex['f']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</td>
<td><input type="text" name="icon_<?php } else { echo '___aapw'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="2" maxlength="2" value="<?php } else { echo '___aapx'; } ?><?php echo $ur['icon']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" disabled="true"></td>
<td><?php } else { echo '___aapn'; } ?><?php echo $clubinfo[$ur['club']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="motto_<?php } else { echo '___aapy'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="20" value="<?php } else { echo '___aapz'; } ?><?php echo $ur['motto']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" disabled="true"></td>
<td><input type="text" name="killmsg_<?php } else { echo '___aapA'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="20" value="<?php } else { echo '___aapz'; } ?><?php echo $ur['killmsg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" disabled="true"></td>
<td><input type="text" name="lastword_<?php } else { echo '___aapB'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="20" value="<?php } else { echo '___aapz'; } ?><?php echo $ur['lastword']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" disabled="true"></td>
<td>
<input type="submit" value="修改" disabled="true">
</td>
<?php } else { echo '___aapC'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td><input type="checkbox" id="user_<?php } else { echo '___aapl'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"  name="user_<?php } else { echo '___aapD'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" value="<?php } else { echo '___aade'; } ?><?php echo $ur['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"></td>
<td><?php } else { echo '___aapE'; } ?><?php echo $ur['username']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="pass_<?php } else { echo '___aapo'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="20" value=""></td>
<td><?php } else { echo '___aapF'; } ?><?php echo $urgroup[$ur['groupid']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>第<?php } else { echo '___aapq'; } ?><?php echo $ur['lastgame']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>局</td>
<td><?php } else { echo '___aapr'; } ?><?php echo $ur['ip']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $ur['credits']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>
<select name="gender_<?php } else { echo '___aaps'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<option value="0" 
<?php } else { echo '___aapG'; } ?><?php if($ur['gender']==0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $ursex['0']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="m" 
<?php } else { echo '___aapu'; } ?><?php if($ur['gender']=='m') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $ursex['m']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="f" 
<?php } else { echo '___aapv'; } ?><?php if($ur['gender']=='f') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $ursex['f']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</td>
<td><input type="text" name="icon_<?php } else { echo '___aapw'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="2" maxlength="2" value="<?php } else { echo '___aapx'; } ?><?php echo $ur['icon']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"></td>
<td><?php } else { echo '___aapE'; } ?><?php echo $clubinfo[$ur['club']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="motto_<?php } else { echo '___aapy'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="60" value="<?php } else { echo '___aapH'; } ?><?php echo $ur['motto']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"></td>
<td><input type="text" name="killmsg_<?php } else { echo '___aapI'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="60" value="<?php } else { echo '___aapH'; } ?><?php echo $ur['killmsg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"></td>
<td><input type="text" name="lastword_<?php } else { echo '___aapJ'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="20" maxlength="60" value="<?php } else { echo '___aapH'; } ?><?php echo $ur['lastword']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"></td>
<td>
<input type="submit" value="修改" onclick="$('urcmd').value='edit_<?php } else { echo '___aapK'; } ?><?php echo $n?>_<?php echo $ur['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>'">
</td>
<?php } else { echo '___aapL'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></tr>
<?php } else { echo '___aapM'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td colspan=2><input type="checkbox" name="user_all" onchange="for(i=0; i<=<?php } else { echo '___aapN'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>;i++){if(! $('user_'+i).disabled){if(this.checked==true){$('user_'+i).checked=true}else{$('user_'+i).checked=false}}}">全选</td>
<td colspan=12 style="text-align:center;">
<input type="submit" name="submit" value="封停选中玩家" onclick="$('urcmd').value='ban'">
<input type="submit" name="submit" value="解封选中玩家" onclick="$('urcmd').value='unban'">
<input type="submit" name="submit" value="删除选中玩家" onclick="$('urcmd').value='del'">
</td>
</tr>
<?php } else { echo '___aapO'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></table>
<?php } else { echo '___aapP'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></form>
<form method="post" name="backtolist" onsubmit="admin.php">
<input type="hidden" name="mode" value="urlist">
<input type="hidden" name="command" value="">
<input type="submit" name="submit" value="返回玩家帐户管理">
</form>
<?php } else { echo '___aapQ'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="urlist" onsubmit="admin.php">
<input type="hidden" name="mode" value="urlist">
<input type="hidden" name="pagecmd" id="pagecmd" value="">
<input type="hidden" name="urcmd" value="list">
<table class="admin">
<tr>
<th>搜索指定帐户</th>
<th>查看帐户列表</th>
</tr>
<tr>
<td>
条件：
<select name="checkmode">
<option value="username" selected>用户名
<option value="ip">用户IP
</select>
类似
<input size="30" type="text" name="checkinfo" id="checkinfo" maxlength="30" />
</td>
<td>
按：
<select name="urorder">
<option value="groupid" selected>用户所属组
<option value="lastgame">最新游戏
<option value="uid">用户编号
</select>
<select name="urorder2">
<option value="DESC" selected>降序排列
<option value="ASC">升序排列
</select>
</td>
</tr>
<tr>
<td style="text-align:center;"><input style="width:100px;height:30px;" type="submit" value="搜索" onclick="$('pagecmd').value='find';"></td>
<td style="text-align:center;"><input style="width:100px;height:30px;" type="submit" value="查看" onclick="$('pagecmd').value='check';"></td>
</tr>
</table>

</form>
<?php } else { echo '___aapR'; } ?><?php } ?>

