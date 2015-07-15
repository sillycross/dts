<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table border="0" width="720" height="100%" cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td>
<table border="0" width="720" cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td width="73"></td><td width="82"></td><td width="72"></td><td width="123"></td><td width="61"></td><td width="82"></td><td width="221"></td>
</tr>
<tr>
<td colspan="3" class="b1"><span><?php } else { echo '___aat9'; } ?><?php echo $name?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td colspan="1" class="b1"><span><?php } else { echo '___aat.'; } ?><?php echo $sexinfo[$gd]?><?php echo $sNo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>号</span></td>
<?php } else { echo '___aat-'; } ?><?php if(defined('MOD_WEATHER')) { include template('MOD_WEATHER_PROFILE_WEATHER'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td colspan="2" class="b1"></td>
<?php } else { echo '___aaua'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td colspan="1" class="b1"><span><?php } else { echo '___aaub'; } ?><?php echo $month?>月<?php echo $day?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>日 星期<?php } else { echo '___aam7'; } ?><?php echo $week["$wday"]?> <?php echo $hour?>:<?php echo $min?>
<?php if($gamestate == 40 ) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">连斗</span>
<?php } else { echo '___aauc'; } ?><?php } if($gamestate == 50 ) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">死斗</span>
<?php } else { echo '___aaud'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr height="165">
<td colspan="2" valign="top">
<table border="0" width=156 height=100% cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td width="73"></td><td width="82"></td>
</tr>
<tr>
<td colspan="2" height="80" class="b3"><span><img src="img/<?php } else { echo '___aaue'; } ?><?php echo $iconImg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" border="0" style="width:140;height:80" 
<?php } else { echo '___aauf'; } ?><?php if($hp==0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>style="filter:Xray()"
<?php } else { echo '___aact'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> /></span></td>
</tr>
<tr title="你的攻击力是基础攻击力与武器攻击力的和，武器攻击力等于武器的效果值×2">
<td class="b2"><span>攻击力</span></td>
<td class="b3"><span><?php } else { echo '___aaug'; } ?><?php echo $att?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> + 
<?php } else { echo '___aauh'; } ?><?php echo $wepe*2; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr title="你的防御力是基础防御力与所有防具防御力的和，防具防御力等于防具的效果值">
<td class="b2"><span>防御力</span></td>
<td class="b3"><span><?php } else { echo '___aaui'; } ?><?php echo $def?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> + <?php } else { echo '___aauj'; } ?><?php echo $ardef?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<?php } else { echo '___aacA'; } ?><?php if(defined('MOD_POSE')) { include template('MOD_POSE_POSE_PROFILE_CMD'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"></td><td class="b3"></td>
<?php } else { echo '___aauk'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></tr>
<tr>
<?php } else { echo '___aaul'; } ?><?php if(defined('MOD_TACTIC')) { include template('MOD_TACTIC_TACTIC_PROFILE_CMD'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"></td><td class="b3"></td>
<?php } else { echo '___aauk'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></tr>
<tr>
<td class="b2">■■：</td>
<td class="b3">■■。</td>
</tr>
</table>
</td>
<td colspan="2" valign="top">
<table border="0" width="196" height=100% cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td width="72"></td><td width="123">
</tr>
<tr>
<td class="b2"><span>等级</span></td>
<td class="b3"><span>Lv. <?php } else { echo '___aaum'; } ?><?php echo $lvl?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2"><span>经验值</span></td>
<td class="b3"><span><?php } else { echo '___aaun'; } ?><?php echo $exp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacJ'; } ?><?php echo $upexp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2"><span>队伍</span></td>
<td class="b3"><span>
<?php } else { echo '___aauo'; } ?><?php if($teamID && $gamestate < 40 ) { ?>
<?php echo $teamID?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> 无 
<?php } else { echo '___aaup'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<?php } else { echo '___aap-'; } ?><?php if(defined('MOD_CLUBBASE')) { include template('MOD_CLUBBASE_CLUB_PROFILE'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"></td><td class="b3"></td>
<?php } else { echo '___aauk'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></tr>
<tr>
<td class="b2"><span>金钱</span></td>
<td class="b3"><span><?php } else { echo '___aauq'; } ?><?php echo $money?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 元</span></td>
</tr>
<tr>
<?php } else { echo '___aaur'; } ?><?php if(defined('MOD_WOUND')) { include template('MOD_WOUND_WOUND_PROFILE'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"></td><td class="b3"></td>
<?php } else { echo '___aauk'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></tr>
<tr>
<td class="b2"><span>体力</span></td>
<td class="b3"><span><span class="
<?php } else { echo '___aaus'; } ?><?php if($sp <= $msp*0.2) { ?>
grey
<?php } elseif($sp <= $msp*0.5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>yellow
<?php } else { echo '___aaut'; } ?><?php } else { ?>
clan
<?php } ?>
"><?php echo $sp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacJ'; } ?><?php echo $msp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></span></td>
</tr>
<tr>
<td class="b2"><span>生命</span></td>
<td class="b3"><span><span class="
<?php } else { echo '___aauu'; } ?><?php if($hp <= $mhp*0.2) { ?>
red
<?php } elseif($hp <= $mhp*0.5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>yellow
<?php } else { echo '___aaut'; } ?><?php } else { ?>
clan
<?php } ?>
"><?php echo $hp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacJ'; } ?><?php echo $mhp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></span></td>
</tr>
<tr>
<td class="b2"><span>歌魂</span></td>
<td class="b3"><span class="
<?php } else { echo '___aauv'; } ?><?php if($ss <= $mss*0.2) { ?>
red
<?php } elseif($ss <= $mss*0.5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>yellow
<?php } else { echo '___aaut'; } ?><?php } else { ?>
clan
<?php } ?>
"><?php echo $ss?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacJ'; } ?><?php echo $mss?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
</table>
</td>
<td colspan="2" valign="top">
<table border="0" width="144" height=100% cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td width="61"></td><td width="82"></td>
</tr>
<?php } else { echo '___aauw'; } ?><?php if(defined('MOD_WEAPON')) { include template('MOD_WEAPON_PROFILE_WEPSKILL'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td class="b2"><span>怒气</span></td>
<td class="b3"><span>
<?php } else { echo '___aaux'; } ?><?php if($rage >= 30) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow"><?php } else { echo '___aacM'; } ?><?php echo $rage?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } else { ?>
<?php echo $rage?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2"><span>技能点</span></td>
<td class="b3">
<?php } else { echo '___aauy'; } ?><?php if($skillpoint>0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime"><?php } else { echo '___aauz'; } ?><?php echo $skillpoint?>
<?php } else { ?>
<?php echo $skillpoint?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2"><span>击杀数</span></td>
<td class="b3"><span><?php } else { echo '___aauA'; } ?><?php echo $killnum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
</table>
</td>
<td colspan="1" valign="top">
<table border="0" width="221" height="100%" cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td width="221"></td>
</tr>
<tr>
<td class="b3">
<div>
<table border="0" width=215px height=160px cellspacing="0" cellpadding="0" style="position:relative">
<tr height=160px>
<td width=160px background="img/state1.gif" style="position:relative;background-repeat:no-repeat;background-position:right top;">
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;z-index:1;top:0;left:0;">
<?php } else { echo '___aauB'; } ?><?php if(defined('MOD_WOUND')) { include template('MOD_WOUND_WOUND_PICTURE'); } if($hp <= 0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/dead.gif" style="position:absolute;top:120;left:6;width:94;height:40">
<?php } else { echo '___aauC'; } ?><?php } elseif($hp <= $mhp*0.2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/danger.gif" style="position:absolute;top:120;left:5;width:95;height:37">
<?php } else { echo '___aauD'; } ?><?php } elseif($hp <= $mhp*0.5 || $inf!='') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/caution.gif" style="position:absolute;top:120;left:5;width:95;height:36">
<?php } else { echo '___aauE'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/fine.gif" style="position:absolute;top:120;left:12;width:81;height:38">
<?php } else { echo '___aauF'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;top:0px;left:105px;z-index:0;">
<?php } else { echo '___aauG'; } ?><?php echo $newspimg?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
</td>
<td width=55px background="img/state2.gif" style="position:relative;background-repeat:no-repeat;background-position:left top;">
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;top:0px;right:55px;z-index:0;">
<?php } else { echo '___aauH'; } ?><?php echo $newhpimg?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td height="10" class="b5"></td>
</tr>
<tr>
<td>
          <TABLE border="0" cellSpacing=0 cellPadding=0 height=140 width=720>
              <tr>
              <td>
      
<?php } else { echo '___aauI'; } ?><?php if(defined('MOD_ITEMMAIN')) { include template('MOD_ITEMMAIN_PROFILE_EQUIPMENTS'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>
<?php } else { echo '___aatb'; } ?><?php if(defined('MOD_ITEMMAIN')) { include template('MOD_ITEMMAIN_PROFILE_BACKPACK'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>              </td>
          </tr>
</TABLE>
</td>
</tr>
</table><?php } else { echo '___aauJ'; } ?>