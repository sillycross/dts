<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_WEAPON__VARS__nowep,$___LOCAL_WEAPON__VARS__nosta,$___LOCAL_WEAPON__VARS__skilltypeinfo,$___LOCAL_WEAPON__VARS__attinfo,$___LOCAL_WEAPON__VARS__skillinfo,$___LOCAL_WEAPON__VARS__wep_equip_list,$___LOCAL_WEAPON__VARS__counter_obbs,$___LOCAL_WEAPON__VARS__rangeinfo,$___LOCAL_WEAPON__VARS__hitrate_obbs,$___LOCAL_WEAPON__VARS__hitrate_max_obbs, $___LOCAL_WEAPON__VARS__hitrate_r,$___LOCAL_WEAPON__VARS__dmg_fluc,$___LOCAL_WEAPON__VARS__skill_dmg,$___LOCAL_WEAPON__VARS__wepimprate,$___LOCAL_WEAPON__VARS__wepdeathstate; $nowep=&$___LOCAL_WEAPON__VARS__nowep; $nosta=&$___LOCAL_WEAPON__VARS__nosta; $skilltypeinfo=&$___LOCAL_WEAPON__VARS__skilltypeinfo; $attinfo=&$___LOCAL_WEAPON__VARS__attinfo; $skillinfo=&$___LOCAL_WEAPON__VARS__skillinfo; $wep_equip_list=&$___LOCAL_WEAPON__VARS__wep_equip_list; $counter_obbs=&$___LOCAL_WEAPON__VARS__counter_obbs; $rangeinfo=&$___LOCAL_WEAPON__VARS__rangeinfo; $hitrate_obbs=&$___LOCAL_WEAPON__VARS__hitrate_obbs; $hitrate_max_obbs=&$___LOCAL_WEAPON__VARS__hitrate_max_obbs;  $hitrate_r=&$___LOCAL_WEAPON__VARS__hitrate_r; $dmg_fluc=&$___LOCAL_WEAPON__VARS__dmg_fluc; $skill_dmg=&$___LOCAL_WEAPON__VARS__skill_dmg; $wepimprate=&$___LOCAL_WEAPON__VARS__wepimprate; $wepdeathstate=&$___LOCAL_WEAPON__VARS__wepdeathstate;   if(is_array($skilltypeinfo)) { foreach($skilltypeinfo as $key => $value) { if((in_array($key,$skillinfo))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td class="b2"><span>
<?php } else { echo '___aae5'; } ?><?php if(($$key >= 100)) { ?>
<?php echo $value?>熟
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey">
<?php } else { echo '___aae6'; } ?><?php echo $value?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>熟</span>
<?php } else { echo '___aae7'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b3"><span>
<?php } else { echo '___aae8'; } ?><?php eval('echo $'.$key.';'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<?php } else { echo '___aae9'; } ?><?php } } } ?>

