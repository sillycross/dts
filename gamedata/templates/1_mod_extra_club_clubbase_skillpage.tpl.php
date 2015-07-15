<?php if(!defined('IN_GAME')) exit('Access Denied'); if(!defined('IN_HELP')) { include template('command'); ?>
<input type="hidden" id="skillpara1" name="skillpara1" value="">
<input type="hidden" id="skillpara2" name="skillpara2" value="">
<input type="hidden" id="skillpara3" name="skillpara3" value="">
<input type="hidden" id="skillpara4" name="skillpara4" value="">
<input type="hidden" id="skillpara5" name="skillpara5" value="">
<?php } if(defined('IN_HELP')) { \bubblebox\bubblebox_set_style('id:club'.$nowclub.';height:514;width:518;cancellable:1;'); } else { \bubblebox\bubblebox_set_style('id:clubskill;height:514;width:518;cancellable:1;'); } include template('MOD_BUBBLEBOX_START'); ?>
<p style="margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px;">
<?php if(!defined('IN_HELP')) { ?>
<!--于是经过各种边框，最后工作区的大小是height:514px width:509px--> 
<?php if($club==0) { ?>
你还没有选择内定称号，请在主界面中选择一个内定称号后再查看技能。<br>
<span class="yellow">点击技能界面外任意位置可以返回主界面。</span><br><br>
<?php } else { \player\update_sdata(); ?>
你目前有<span class="lime"><?php echo $skillpoint?></span>点技能点供自由分配，升级时可以获取新的技能点。<br>
以下是你的内定称号的技能列表。<br><br>
<?php } } else { eval(import_module('clubbase')); ?>
以下是<?php echo $clubinfo[$nowclub]?>称号的技能列表。<br>
技能点在升级时获得。鼠标移动到非开局解锁的技能上可以查看其描述。<br><br>
<?php } ?>
</p>
<TABLE border="0" cellSpacing=0 cellPadding=0 width=509px>
<tr>
<td width="0"></td>
<TD class=b1 width="40"><span>名称</span></TD>
<TD class=b1><span></span></TD>
</tr>
<?php \clubbase\get_skillpage(); ?>
</table>
<br>
<p>

<input class="cmdbutton" onclick="bubblebox_hide_all();" value="返回主界面" type="button">

&nbsp;&nbsp;&nbsp; 提示:点击技能界面外任意位置也可以返回主界面<br>
</p>
<?php include template('MOD_BUBBLEBOX_END'); if(!defined('IN_HELP')) { ?>
<img style="display:none;" type="hidden" src="img/blank.png" onload="bubblebox_show('clubskill');">
<?php } $mode='command'; ?>
