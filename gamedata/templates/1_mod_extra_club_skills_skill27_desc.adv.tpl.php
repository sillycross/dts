<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=27; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
流火
</span>
<?php } else { echo '___aajm'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
你的<span class="red">火焰</span>/<span class="red">灼焰</span>伤害将对敌方一件随机防具造成<span class="yellow">1点</span>/<span class="yellow">2点</span>耐久损伤。<br>
如防具破碎（或该部位无防具），敌人烧伤。<br>
在你发动“<span class="yellow">聚能</span>”时效果对敌人的所有防具发生且损伤<span class="yellow">+1</span>点。
</span></td>
<td class=b3 width=46>

</td>
<?php } else { echo '___aajn'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
7级时解锁
</span>
<?php } else { echo '___aafL'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

