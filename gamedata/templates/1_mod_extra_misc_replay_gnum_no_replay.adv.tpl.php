<?php if(!defined('IN_GAME')) exit('Access Denied'); \bubblebox\bubblebox_set_style('id:gnum_replay_error;height:100;width:500;cancellable:1;margin-left:20px;margin-right:20px;margin-top:20px; margin-bottom:20px;'); include template('MOD_BUBBLEBOX_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div align="center">
<table CellPadding=0 CellSpacing=0 height=100px>
<tr>
<td width=150px>
<img style="float:left; margin-right:10px;" src="img/n_42.gif">
</td>
<td width=350px>
<span class="evergreen" style="font-size:14px">
『额，不好意思，BR法推进特别委员会的档案室中似乎并没有留存第<span class="yellow"><?php } else { echo '___aaaD'; } ?><?php echo $gnum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>回「计划」的影像带存档。<br>
大概是年代太久远了吧，对计划实施过程全程录像也是最近才刚开始实行的事情了。保管员搞丢了也说不定。<br>
还望阁下多多支持我们委员会的工作。』
</span>
</td>
</tr></table>
</div>
<?php } else { echo '___aaaE'; } ?><?php include template('MOD_BUBBLEBOX_END'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript">bubblebox_show('gnum_replay_error');</script><?php } else { echo '___aaaF'; } ?>