<?php if(!defined('IN_GAME')) exit('Access Denied'); __MODULE_NULLFUNCTION__();  $___TEMP_SKILL_ID=33; $nchoice = \skillbase\skill_getvalue(33,'choice');  include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
应变
</span>
<?php } else { echo '___aaij'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><div style="margin-top:8px; margin-bottom:8px;">
<span style="text-align:left; display:inline-block;">
设你从“<span class="yellow">格挡</span>”技能中获得了<span class="yellow">x</span>点额外防御。<br>
你可以随意于下列三个状态间切换：<br>
<table>
<tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php } else { echo '___aaik'; } ?><?php if($nchoice==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span >
<?php } else { echo '___aail'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>(1) 防御力再次增加<span class="yellow">x</span>点，武器效果视为降低<span class="yellow">0.7x</span>点 
<?php } else { echo '___aaim'; } ?><?php if($nchoice==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td><td>
<?php } else { echo '___aaeF'; } ?><?php if($nchoice==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime">[当前选择]</span>
<?php } else { echo '___aain'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill33_special';$('subcmd').value='upgrade';$('skillpara1').value='1';postCmd('gamecmd','command.php');this.disabled=true;" value="选择">
<?php } else { echo '___aaio'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td></tr>
<tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php } else { echo '___aaip'; } ?><?php if($nchoice==2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span >
<?php } else { echo '___aail'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>(2) 无效果 
<?php } else { echo '___aaiq'; } ?><?php if($nchoice==2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td><td>
<?php } else { echo '___aaeF'; } ?><?php if($nchoice==2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime">[当前选择]</span>
<?php } else { echo '___aain'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill33_special';$('subcmd').value='upgrade';$('skillpara1').value='2';postCmd('gamecmd','command.php');this.disabled=true;" value="选择">
<?php } else { echo '___aair'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td></tr>
<tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php } else { echo '___aaip'; } ?><?php if($nchoice==3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span >
<?php } else { echo '___aail'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>(3) 武器效果视为增加<span class="yellow">0.7x</span>点，防御力降低<span class="yellow">x</span>点  
<?php } else { echo '___aais'; } ?><?php if($nchoice==3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td><td>
<?php } else { echo '___aaeF'; } ?><?php if($nchoice==3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime">[当前选择]</span>
<?php } else { echo '___aain'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill33_special';$('subcmd').value='upgrade';$('skillpara1').value='3';postCmd('gamecmd','command.php');this.disabled=true;" value="选择">
<?php } else { echo '___aait'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td></tr>
</table>
属性伤害不受这个技能影响。
</span>
</div></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aaiu'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aagf'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

