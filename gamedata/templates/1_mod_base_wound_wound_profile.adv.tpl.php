<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_WOUND__VARS__inf_place,$___LOCAL_WOUND__VARS__infinfo,$___LOCAL_WOUND__VARS__infname,$___LOCAL_WOUND__VARS__infskillinfo,$___LOCAL_WOUND__VARS__wep_infatt,$___LOCAL_WOUND__VARS__wep_infobbs,$___LOCAL_WOUND__VARS__inf_recover_sp_cost; $inf_place=&$___LOCAL_WOUND__VARS__inf_place; $infinfo=&$___LOCAL_WOUND__VARS__infinfo; $infname=&$___LOCAL_WOUND__VARS__infname; $infskillinfo=&$___LOCAL_WOUND__VARS__infskillinfo; $wep_infatt=&$___LOCAL_WOUND__VARS__wep_infatt; $wep_infobbs=&$___LOCAL_WOUND__VARS__wep_infobbs; $inf_recover_sp_cost=&$___LOCAL_WOUND__VARS__inf_recover_sp_cost;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span>受伤部位</span></td>
<td class="b3">
<span>
<?php } else { echo '___aafa'; } ?><?php if($inf) { if(is_array($infinfo)) { foreach($infinfo as $key => $val) { if(strpos($inf,$key)!==false) { ?>
<?php echo $val?>
<?php } } } } else { ?>
无
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</td><?php } else { echo '___aafb'; } ?>