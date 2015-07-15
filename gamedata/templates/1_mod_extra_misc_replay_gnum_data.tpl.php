<?php if(!defined('IN_GAME')) exit('Access Denied'); \bubblebox\bubblebox_set_style('id:gnum_replay;height:400;width:520;cancellable:1;margin-left:20px;margin-right:20px;margin-top:20px; margin-bottom:20px;'); include template('MOD_BUBBLEBOX_START'); ?>
<div align="center">
<table CellPadding=0 CellSpacing=0>
<tr>
<td width=150px>
<img style="float:left; margin-right:10px;" src="img/n_42.gif">
</td>
<td width=350px>
<span class="evergreen" style="font-size:14px">
『那么，这些就是刚刚解密的，由BR法推进特别委员会制作的，第<span class="yellow"><?php echo $gnum?></span>回「计划」的实施过程影像带。<br>
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
<?php if(is_array($lis)) { foreach($lis as $key) { ?>
<tr>
<td class="b3" ><?php echo $key['repname']?>
<?php if(isset($key['is_winner'])) { ?>
<span class="red">&nbsp;(优胜者)</span>
<?php } ?>
</td>
<td class="b3"><?php echo $key['repopcnt']?></td>
<td class="b3">
<input type="button" style="width:60px;" value="观看录像" onclick="window.location.href='replay.php?repid=<?php echo $key['link']?>'">
</td>
</tr>
<?php } } ?>
</table>
</div>
<?php include template('MOD_BUBBLEBOX_END'); ?>
<script type="text/javascript">bubblebox_show('gnum_replay');</script>