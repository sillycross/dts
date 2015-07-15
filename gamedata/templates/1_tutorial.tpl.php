<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
﻿
<?php include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div class="subtitle" >游戏教程</div>
<center>
<?php } else { echo '___aakC'; } ?><?php if(!$tmode) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table>
<tr>
<td align="left" style="width:220px;">
<img border="0" src="img/story_3.jpg" style="width:200;height:185;">
<p>
那边的，对，就是你，给我站住。
</p>
<p>
你觉得凭你目前的实力，足够在外面的战场上活下来么？如果你对最基本的大逃杀知识都不了解，那么你的生存时间不会超过10分钟。
</p>
<p>
右边的就是<span class="yellow">基本界面</span>。你可以在上面看到你的各项数据，其中和你最性命攸关的就是你的<span class="yellow">生命值</span>。
</p>
<p>
接下来我会在<span class="yellow">移动探索</span>、<span class="yellow">作战和逃跑</span>、<span class="yellow">使用和装备道具</span>、<span class="yellow">包扎伤口和异常处理</span>、<span class="yellow">访问商店和医院</span>、<span class="yellow">游戏状态和禁区</span>等几个方面作出最基本的示范。
</p>
<p>
掌握它们能确保你最低限度地利用游戏系统。至于更高层面的作战技巧则要靠你的悟性了。
</p>
</td>
<td>
<img border="0" src="img/tutorial/t1.jpg">
</td>
</tr>
</table>
<?php } else { echo '___aakD'; } ?><?php } elseif($tmode==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table>
<tr>
<td align="left" style="width:220px;">
<img border="0" src="img/story_3.jpg" style="width:200;height:185;">
<p>
接下来是<span class="yellow">移动和探索</span>。
</p>
<p>
移动可以使你改变所在地点。探索则会在当前所在地点搜索。
</p>
<p>
移动和探索都有可能发生<span class="yellow">遭遇敌人</span>、<span class="yellow">拾取物品</span>或者<span class="yellow">遭遇突发事件</span>中的任何一个，也有可能什么都没遇上。
</p>
<p>
出生时位于无月之影，初始即为禁区，无法进行搜索，必须移动到其他地图。
</p>
</td>
<td>
<img border="0" src="img/tutorial/t21.jpg"> 
<img border="0" src="img/tutorial/t22.jpg">
</td>
</tr>
</table>
<?php } else { echo '___aakE'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></center>
<form method="post"  action="valid.php">
<input type="hidden" name="mode" value="tutorial">
<input type="hidden" name="tmode" value="<?php } else { echo '___aakF'; } ?><?php echo $nexttmode?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="submit" value="下一篇教程">
<input type="button" value="进入大逃杀战场" onclick="window.location.href='game.php'">
</form>
<?php } else { echo '___aakG'; } ?><?php include template('footer'); ?>

