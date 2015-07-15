<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL44__VARS__upgradecost,$___LOCAL_SKILL44__VARS__dmgreduction; $upgradecost=&$___LOCAL_SKILL44__VARS__upgradecost; $dmgreduction=&$___LOCAL_SKILL44__VARS__dmgreduction;   $___TEMP_SKILL_ID=44; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
金刚
</span>
<?php } else { echo '___aahJ'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aafU'; } ?><?php $clv=(int)\skillbase\skill_getvalue(44,'lvl'); $choice=(int)\skillbase\skill_getvalue(44,'choice'); $totlv = count($upgradecost)-1;  $redperc = min(50,$dmgreduction[$clv]*$def); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime">当前级别<?php } else { echo '___aafV'; } ?><?php echo $clv?>/<?php echo $totlv?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>&nbsp;
<?php } else { echo '___aafW'; } ?><?php if($upgradecost[$clv] !== -1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>消耗<span class="lime"><?php } else { echo '___aafX'; } ?><?php echo $upgradecost[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点技能点升至下一级
<?php } else { echo '___aafY'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
每一点基础防御降低<span class="yellow"><?php } else { echo '___aahK'; } ?><?php echo $dmgreduction[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span>固定伤害或爆炸伤害(最高50%)<br>
当前选择：降低你受到的<span class="yellow">
<?php } else { echo '___aahL'; } ?><?php if($choice==0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>固定伤害
<?php } else { echo '___aahM'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>爆炸伤害
<?php } else { echo '___aahN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span> 
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill44_special';$('subcmd').value='upgrade';$('skillpara1').value=2;postCmd('gamecmd','command.php');this.disabled=true;" value="切换">
</span></td>
<td class=b3 width=46>
<?php } else { echo '___aahO'; } ?><?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill44_special';$('subcmd').value='upgrade';$('skillpara1').value=1;postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { echo '___aahP'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="升级">
<?php } else { echo '___aaf3'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaQ'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aaf.'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

