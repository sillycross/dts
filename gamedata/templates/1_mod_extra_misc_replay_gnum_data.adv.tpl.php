<?php if(!defined('IN_GAME')) exit('Access Denied'); \bubblebox\bubblebox_set_style('id:gnum_replay;height:400;width:520;cancellable:1;margin-left:20px;margin-right:20px;margin-top:20px; margin-bottom:20px;'); include template('MOD_BUBBLEBOX_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div align="center">
<table CellPadding=0 CellSpacing=0>
<tr>
<td width=150px>
<img style="float:left; margin-right:10px;" src="img/n_42.gif">
</td>
<td width=350px>
<span class="evergreen" style="font-size:14px">
『那么，这些就是刚刚解密的，由BR法推进特别委员会制作的，第<span class="yellow"><?php } else { echo '___aaaG'; } ?><?php echo $gnum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>回「计划」的实施过程影像带。<br>
除了那些一次操作都没有进行就死掉的人，其他人的影像都在这里。还望阁下多多支持我们委员会的工作。』
</span>
</td>
</tr></table>
<br>
<table>
<tr>
<td class="b1" width=200px>
参与者
</td>
<td class="b1" width=80px>
行动数目
</td>
<td class="b1" width=80px>
操作
</td>
</tr>
<?php } else { echo '___aaaH'; } ?><?php if(is_array($lis)) { foreach($lis as $key) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td class="b3" ><?php } else { echo '___aaaI'; } ?><?php echo $key['repname']?>
<?php if(isset($key['is_winner'])) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">&nbsp;(优胜者)</span>
<?php } else { echo '___aaaJ'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td class="b3"><?php } else { echo '___aaaK'; } ?><?php echo $key['repopcnt']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td class="b3">
<input type="button" style="width:60px;" value="观看录像" onclick="window.location.href='replay.php?repid=<?php } else { echo '___aaaL'; } ?><?php echo $key['link']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>'">
</td>
</tr>
<?php } else { echo '___aaaM'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></table>
</div>
<?php } else { echo '___aaaN'; } ?><?php include template('MOD_BUBBLEBOX_END'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript">bubblebox_show('gnum_replay');</script><?php } else { echo '___aaaO'; } ?>