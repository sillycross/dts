<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_METMAN__VARS__tdata,$___LOCAL_METMAN__VARS__w_upexp,$___LOCAL_METMAN__VARS__battle_title,$___LOCAL_METMAN__VARS__hideflag,$___LOCAL_METMAN__VARS__hpinfo,$___LOCAL_METMAN__VARS__spinfo,$___LOCAL_METMAN__VARS__rageinfo,$___LOCAL_METMAN__VARS__wepeinfo,$___LOCAL_METMAN__VARS__metman_obbs,$___LOCAL_METMAN__VARS__w_pid, $___LOCAL_METMAN__VARS__w_type,$___LOCAL_METMAN__VARS__w_name,$___LOCAL_METMAN__VARS__w_pass,$___LOCAL_METMAN__VARS__w_gd,$___LOCAL_METMAN__VARS__w_sNo,$___LOCAL_METMAN__VARS__w_icon,$___LOCAL_METMAN__VARS__w_club,$___LOCAL_METMAN__VARS__w_endtime,$___LOCAL_METMAN__VARS__w_cdsec,$___LOCAL_METMAN__VARS__w_cdmsec, $___LOCAL_METMAN__VARS__w_cdtime,$___LOCAL_METMAN__VARS__w_action,$___LOCAL_METMAN__VARS__w_hp,$___LOCAL_METMAN__VARS__w_mhp,$___LOCAL_METMAN__VARS__w_sp,$___LOCAL_METMAN__VARS__w_msp,$___LOCAL_METMAN__VARS__w_ss,$___LOCAL_METMAN__VARS__w_mss,$___LOCAL_METMAN__VARS__w_att,$___LOCAL_METMAN__VARS__w_def, $___LOCAL_METMAN__VARS__w_pls,$___LOCAL_METMAN__VARS__w_lvl,$___LOCAL_METMAN__VARS__w_exp,$___LOCAL_METMAN__VARS__w_money,$___LOCAL_METMAN__VARS__w_rp,$___LOCAL_METMAN__VARS__w_bid,$___LOCAL_METMAN__VARS__w_inf,$___LOCAL_METMAN__VARS__w_rage,$___LOCAL_METMAN__VARS__w_pose,$___LOCAL_METMAN__VARS__w_tactic, $___LOCAL_METMAN__VARS__w_killnum,$___LOCAL_METMAN__VARS__w_state,$___LOCAL_METMAN__VARS__w_wp,$___LOCAL_METMAN__VARS__w_wk,$___LOCAL_METMAN__VARS__w_wg,$___LOCAL_METMAN__VARS__w_wc,$___LOCAL_METMAN__VARS__w_wd,$___LOCAL_METMAN__VARS__w_wf,$___LOCAL_METMAN__VARS__w_teamID,$___LOCAL_METMAN__VARS__w_teamPass, $___LOCAL_METMAN__VARS__w_wep,$___LOCAL_METMAN__VARS__w_wepk,$___LOCAL_METMAN__VARS__w_wepe,$___LOCAL_METMAN__VARS__w_weps,$___LOCAL_METMAN__VARS__w_wepsk,$___LOCAL_METMAN__VARS__w_arb,$___LOCAL_METMAN__VARS__w_arbk,$___LOCAL_METMAN__VARS__w_arbe,$___LOCAL_METMAN__VARS__w_arbs,$___LOCAL_METMAN__VARS__w_arbsk, $___LOCAL_METMAN__VARS__w_arh,$___LOCAL_METMAN__VARS__w_arhk,$___LOCAL_METMAN__VARS__w_arhe,$___LOCAL_METMAN__VARS__w_arhs,$___LOCAL_METMAN__VARS__w_arhsk,$___LOCAL_METMAN__VARS__w_ara,$___LOCAL_METMAN__VARS__w_arak,$___LOCAL_METMAN__VARS__w_arae,$___LOCAL_METMAN__VARS__w_aras,$___LOCAL_METMAN__VARS__w_arask, $___LOCAL_METMAN__VARS__w_arf,$___LOCAL_METMAN__VARS__w_arfk,$___LOCAL_METMAN__VARS__w_arfe,$___LOCAL_METMAN__VARS__w_arfs,$___LOCAL_METMAN__VARS__w_arfsk,$___LOCAL_METMAN__VARS__w_art,$___LOCAL_METMAN__VARS__w_artk,$___LOCAL_METMAN__VARS__w_arte,$___LOCAL_METMAN__VARS__w_arts,$___LOCAL_METMAN__VARS__w_artsk, $___LOCAL_METMAN__VARS__w_itm0,$___LOCAL_METMAN__VARS__w_itmk0,$___LOCAL_METMAN__VARS__w_itme0,$___LOCAL_METMAN__VARS__w_itms0,$___LOCAL_METMAN__VARS__w_itmsk0,$___LOCAL_METMAN__VARS__w_itm1,$___LOCAL_METMAN__VARS__w_itmk1,$___LOCAL_METMAN__VARS__w_itme1,$___LOCAL_METMAN__VARS__w_itms1,$___LOCAL_METMAN__VARS__w_itmsk1, $___LOCAL_METMAN__VARS__w_itm2,$___LOCAL_METMAN__VARS__w_itmk2,$___LOCAL_METMAN__VARS__w_itme2,$___LOCAL_METMAN__VARS__w_itms2,$___LOCAL_METMAN__VARS__w_itmsk2,$___LOCAL_METMAN__VARS__w_itm3,$___LOCAL_METMAN__VARS__w_itmk3,$___LOCAL_METMAN__VARS__w_itme3,$___LOCAL_METMAN__VARS__w_itms3,$___LOCAL_METMAN__VARS__w_itmsk3, $___LOCAL_METMAN__VARS__w_itm4,$___LOCAL_METMAN__VARS__w_itmk4,$___LOCAL_METMAN__VARS__w_itme4,$___LOCAL_METMAN__VARS__w_itms4,$___LOCAL_METMAN__VARS__w_itmsk4,$___LOCAL_METMAN__VARS__w_itm5,$___LOCAL_METMAN__VARS__w_itmk5,$___LOCAL_METMAN__VARS__w_itme5,$___LOCAL_METMAN__VARS__w_itms5,$___LOCAL_METMAN__VARS__w_itmsk5, $___LOCAL_METMAN__VARS__w_itm6,$___LOCAL_METMAN__VARS__w_itmk6,$___LOCAL_METMAN__VARS__w_itme6,$___LOCAL_METMAN__VARS__w_itms6,$___LOCAL_METMAN__VARS__w_itmsk6,$___LOCAL_METMAN__VARS__w_nskill,$___LOCAL_METMAN__VARS__w_nskillpara,$___LOCAL_METMAN__VARS__w_skillpoint; $tdata=&$___LOCAL_METMAN__VARS__tdata; $w_upexp=&$___LOCAL_METMAN__VARS__w_upexp; $battle_title=&$___LOCAL_METMAN__VARS__battle_title; $hideflag=&$___LOCAL_METMAN__VARS__hideflag; $hpinfo=&$___LOCAL_METMAN__VARS__hpinfo; $spinfo=&$___LOCAL_METMAN__VARS__spinfo; $rageinfo=&$___LOCAL_METMAN__VARS__rageinfo; $wepeinfo=&$___LOCAL_METMAN__VARS__wepeinfo; $metman_obbs=&$___LOCAL_METMAN__VARS__metman_obbs; $w_pid=&$___LOCAL_METMAN__VARS__w_pid;  $w_type=&$___LOCAL_METMAN__VARS__w_type; $w_name=&$___LOCAL_METMAN__VARS__w_name; $w_pass=&$___LOCAL_METMAN__VARS__w_pass; $w_gd=&$___LOCAL_METMAN__VARS__w_gd; $w_sNo=&$___LOCAL_METMAN__VARS__w_sNo; $w_icon=&$___LOCAL_METMAN__VARS__w_icon; $w_club=&$___LOCAL_METMAN__VARS__w_club; $w_endtime=&$___LOCAL_METMAN__VARS__w_endtime; $w_cdsec=&$___LOCAL_METMAN__VARS__w_cdsec; $w_cdmsec=&$___LOCAL_METMAN__VARS__w_cdmsec;  $w_cdtime=&$___LOCAL_METMAN__VARS__w_cdtime; $w_action=&$___LOCAL_METMAN__VARS__w_action; $w_hp=&$___LOCAL_METMAN__VARS__w_hp; $w_mhp=&$___LOCAL_METMAN__VARS__w_mhp; $w_sp=&$___LOCAL_METMAN__VARS__w_sp; $w_msp=&$___LOCAL_METMAN__VARS__w_msp; $w_ss=&$___LOCAL_METMAN__VARS__w_ss; $w_mss=&$___LOCAL_METMAN__VARS__w_mss; $w_att=&$___LOCAL_METMAN__VARS__w_att; $w_def=&$___LOCAL_METMAN__VARS__w_def;  $w_pls=&$___LOCAL_METMAN__VARS__w_pls; $w_lvl=&$___LOCAL_METMAN__VARS__w_lvl; $w_exp=&$___LOCAL_METMAN__VARS__w_exp; $w_money=&$___LOCAL_METMAN__VARS__w_money; $w_rp=&$___LOCAL_METMAN__VARS__w_rp; $w_bid=&$___LOCAL_METMAN__VARS__w_bid; $w_inf=&$___LOCAL_METMAN__VARS__w_inf; $w_rage=&$___LOCAL_METMAN__VARS__w_rage; $w_pose=&$___LOCAL_METMAN__VARS__w_pose; $w_tactic=&$___LOCAL_METMAN__VARS__w_tactic;  $w_killnum=&$___LOCAL_METMAN__VARS__w_killnum; $w_state=&$___LOCAL_METMAN__VARS__w_state; $w_wp=&$___LOCAL_METMAN__VARS__w_wp; $w_wk=&$___LOCAL_METMAN__VARS__w_wk; $w_wg=&$___LOCAL_METMAN__VARS__w_wg; $w_wc=&$___LOCAL_METMAN__VARS__w_wc; $w_wd=&$___LOCAL_METMAN__VARS__w_wd; $w_wf=&$___LOCAL_METMAN__VARS__w_wf; $w_teamID=&$___LOCAL_METMAN__VARS__w_teamID; $w_teamPass=&$___LOCAL_METMAN__VARS__w_teamPass;  $w_wep=&$___LOCAL_METMAN__VARS__w_wep; $w_wepk=&$___LOCAL_METMAN__VARS__w_wepk; $w_wepe=&$___LOCAL_METMAN__VARS__w_wepe; $w_weps=&$___LOCAL_METMAN__VARS__w_weps; $w_wepsk=&$___LOCAL_METMAN__VARS__w_wepsk; $w_arb=&$___LOCAL_METMAN__VARS__w_arb; $w_arbk=&$___LOCAL_METMAN__VARS__w_arbk; $w_arbe=&$___LOCAL_METMAN__VARS__w_arbe; $w_arbs=&$___LOCAL_METMAN__VARS__w_arbs; $w_arbsk=&$___LOCAL_METMAN__VARS__w_arbsk;  $w_arh=&$___LOCAL_METMAN__VARS__w_arh; $w_arhk=&$___LOCAL_METMAN__VARS__w_arhk; $w_arhe=&$___LOCAL_METMAN__VARS__w_arhe; $w_arhs=&$___LOCAL_METMAN__VARS__w_arhs; $w_arhsk=&$___LOCAL_METMAN__VARS__w_arhsk; $w_ara=&$___LOCAL_METMAN__VARS__w_ara; $w_arak=&$___LOCAL_METMAN__VARS__w_arak; $w_arae=&$___LOCAL_METMAN__VARS__w_arae; $w_aras=&$___LOCAL_METMAN__VARS__w_aras; $w_arask=&$___LOCAL_METMAN__VARS__w_arask;  $w_arf=&$___LOCAL_METMAN__VARS__w_arf; $w_arfk=&$___LOCAL_METMAN__VARS__w_arfk; $w_arfe=&$___LOCAL_METMAN__VARS__w_arfe; $w_arfs=&$___LOCAL_METMAN__VARS__w_arfs; $w_arfsk=&$___LOCAL_METMAN__VARS__w_arfsk; $w_art=&$___LOCAL_METMAN__VARS__w_art; $w_artk=&$___LOCAL_METMAN__VARS__w_artk; $w_arte=&$___LOCAL_METMAN__VARS__w_arte; $w_arts=&$___LOCAL_METMAN__VARS__w_arts; $w_artsk=&$___LOCAL_METMAN__VARS__w_artsk;  $w_itm0=&$___LOCAL_METMAN__VARS__w_itm0; $w_itmk0=&$___LOCAL_METMAN__VARS__w_itmk0; $w_itme0=&$___LOCAL_METMAN__VARS__w_itme0; $w_itms0=&$___LOCAL_METMAN__VARS__w_itms0; $w_itmsk0=&$___LOCAL_METMAN__VARS__w_itmsk0; $w_itm1=&$___LOCAL_METMAN__VARS__w_itm1; $w_itmk1=&$___LOCAL_METMAN__VARS__w_itmk1; $w_itme1=&$___LOCAL_METMAN__VARS__w_itme1; $w_itms1=&$___LOCAL_METMAN__VARS__w_itms1; $w_itmsk1=&$___LOCAL_METMAN__VARS__w_itmsk1;  $w_itm2=&$___LOCAL_METMAN__VARS__w_itm2; $w_itmk2=&$___LOCAL_METMAN__VARS__w_itmk2; $w_itme2=&$___LOCAL_METMAN__VARS__w_itme2; $w_itms2=&$___LOCAL_METMAN__VARS__w_itms2; $w_itmsk2=&$___LOCAL_METMAN__VARS__w_itmsk2; $w_itm3=&$___LOCAL_METMAN__VARS__w_itm3; $w_itmk3=&$___LOCAL_METMAN__VARS__w_itmk3; $w_itme3=&$___LOCAL_METMAN__VARS__w_itme3; $w_itms3=&$___LOCAL_METMAN__VARS__w_itms3; $w_itmsk3=&$___LOCAL_METMAN__VARS__w_itmsk3;  $w_itm4=&$___LOCAL_METMAN__VARS__w_itm4; $w_itmk4=&$___LOCAL_METMAN__VARS__w_itmk4; $w_itme4=&$___LOCAL_METMAN__VARS__w_itme4; $w_itms4=&$___LOCAL_METMAN__VARS__w_itms4; $w_itmsk4=&$___LOCAL_METMAN__VARS__w_itmsk4; $w_itm5=&$___LOCAL_METMAN__VARS__w_itm5; $w_itmk5=&$___LOCAL_METMAN__VARS__w_itmk5; $w_itme5=&$___LOCAL_METMAN__VARS__w_itme5; $w_itms5=&$___LOCAL_METMAN__VARS__w_itms5; $w_itmsk5=&$___LOCAL_METMAN__VARS__w_itmsk5;  $w_itm6=&$___LOCAL_METMAN__VARS__w_itm6; $w_itmk6=&$___LOCAL_METMAN__VARS__w_itmk6; $w_itme6=&$___LOCAL_METMAN__VARS__w_itme6; $w_itms6=&$___LOCAL_METMAN__VARS__w_itms6; $w_itmsk6=&$___LOCAL_METMAN__VARS__w_itmsk6; $w_nskill=&$___LOCAL_METMAN__VARS__w_nskill; $w_nskillpara=&$___LOCAL_METMAN__VARS__w_nskillpara; $w_skillpoint=&$___LOCAL_METMAN__VARS__w_skillpoint;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table border="0" width=720px height=380px cellspacing="0" cellpadding="0" valign="middle">
<TR align="center" >
<TD valign="middle" class="b5">
<TABLE border="0" width=720px height=380px align="center" cellspacing="0" cellpadding="0" class="battle">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td>
<table border="0"  cellspacing="0" cellpadding="0" valign="top" width="100%">
<tr>
<td class="b1" colspan=2 height=20px><span>Lv. <?php } else { echo '___aacw'; } ?><?php echo $tdata['lvl']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>                                            
<td class="b1" colspan=2><span><?php } else { echo '___aacx'; } ?><?php echo $tdata['name']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>                                            
<td class="b1" colspan=2><span><?php } else { echo '___aacx'; } ?><?php echo $tdata['sNoinfo']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>                                            
<td class="b5" rowspan=4 width=140px colspan=1 height=80px><IMG src="img/<?php } else { echo '___aacy'; } ?><?php echo $tdata['iconImg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" height=80px border="0" align="middle" 
<?php } else { echo '___aacz'; } ?><?php if($w_hp==0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>style="filter:Xray()"
<?php } else { echo '___aacA'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> /></td>
</tr>
<tr>
<td class="b2" width=75px height=20px><span>怒气</span></td>
<td class="b3" width=90px><span><?php } else { echo '___aacB'; } ?><?php echo $tdata['ragestate']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2" width=75px><span>体力</span></td>
<td class="b3" width=90px><span><?php } else { echo '___aacC'; } ?><?php echo $tdata['spstate']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2" width=100px><span>生命</span></td>
<td class="b3" width=145px><span><?php } else { echo '___aacD'; } ?><?php echo $tdata['hpstate']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2" height=20px><span>武器攻击</span></td>
<td class="b3"><span><?php } else { echo '___aacE'; } ?><?php echo $tdata['wepestate']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2"><span>武器种类</span></td>
<td class="b3"><span>
<?php } else { echo '___aacF'; } ?><?php if((isset($iteminfo[$tdata['wepk']]))) { ?>
<?php echo $iteminfo[$tdata['wepk']]?>
<?php } else { ?>
<?php echo $tdata['wepk']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2"><span>武器</span></td>
<td class="b3"><span><?php } else { echo '___aacG'; } ?><?php echo $tdata['wep']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<?php } else { echo '___aacH'; } ?><?php if(defined('MOD_TACTIC')) { include template('MOD_TACTIC_ENEMY_TACTIC'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span></span></td><td class="b3"><span></span></td>
<?php } else { echo '___aacI'; } ?><?php } if(defined('MOD_POSE')) { include template('MOD_POSE_ENEMY_POSE'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span></span></td><td class="b3"><span></span></td>
<?php } else { echo '___aacI'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span>受伤部位</span></td>
<td class="b3"><span>
<?php } else { echo '___aacJ'; } ?><?php if($tdata['infdata']) { ?>
<?php echo $tdata['infdata']?>
<?php } else { ?>
无
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</TR>
<tr>
<TD class="b3" height="100%">
<span><B><FONT color="#ff0000" size="5" face="黑体"><?php } else { echo '___aacK'; } ?><?php echo $battle_title?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></FONT></B></span>
</TD>
</TR>
<tr>
<td>
<table border="0" width=720px cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td class="b5" rowspan=4 colspan=1 width=140px height=80px><IMG src="img/<?php } else { echo '___aacL'; } ?><?php echo $iconImg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" height=80px border="0" align="middle" 
<?php } else { echo '___aacz'; } ?><?php if($hp==0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>style="filter:Xray()"
<?php } else { echo '___aacA'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> /></td>
<td class="b1" colspan=2 height=20px><span><?php } else { echo '___aacM'; } ?><?php echo $typeinfo[$type]?>(<?php echo $sexinfo[$gd]?><?php echo $sNo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>号)</span></td>
<td class="b1" colspan=2><span><?php } else { echo '___aacN'; } ?><?php echo $name?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b1" colspan=2><span>Lv. <?php } else { echo '___aacO'; } ?><?php echo $lvl?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2" width=100px height=20px><span>生命</span></td>
<td class="b3" width=145px><span><span class="<?php } else { echo '___aacP'; } ?><?php echo $hpcolor?>"><?php echo $hp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacQ'; } ?><?php echo $mhp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></span></td>
<td class="b2" width=75px><span>体力</span></td>
<td class="b3" width=90px><span><?php } else { echo '___aacR'; } ?><?php echo $sp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> / <?php } else { echo '___aacQ'; } ?><?php echo $msp?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2" width=75px><span>怒气</span></td>
<td class="b3" width=90px><span>
<?php } else { echo '___aacS'; } ?><?php if($rage >=30) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow"><?php } else { echo '___aacT'; } ?><?php echo $rage?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php echo $rage?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2" height=20px><span>武器</span></td>
<td class="b3"><span><?php } else { echo '___aacV'; } ?><?php echo $wep?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2"><span>武器种类</span></td>
<td class="b3"><span><?php } else { echo '___aacW'; } ?><?php echo $iteminfo[$wepk]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b2"><span>武器攻击</span></td>
<td class="b3"><span><?php } else { echo '___aacX'; } ?><?php echo $wepe?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<tr>
<td class="b2" height=20px><span>受伤部位</span></td>
<td class="b3">
<span>
<?php } else { echo '___aacY'; } ?><?php if($inf) { if(is_array($infinfo)) { foreach($infinfo as $key => $val) { if(strpos($inf,$key)!==false) { ?>
<?php echo $val?>
<?php } } } } else { ?>
无
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</td>
<?php } else { echo '___aacZ'; } ?><?php if(defined('MOD_POSE')) { include template('MOD_POSE_POSE_PROFILE'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span></span></td><td class="b3"><span></span></td>
<?php } else { echo '___aacI'; } ?><?php } if(defined('MOD_TACTIC')) { include template('MOD_TACTIC_TACTIC_PROFILE'); } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span></span></td><td class="b3"><span></span></td>
<?php } else { echo '___aacI'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></tr>
</table>
</td>
</tr>
</TABLE>
</TD>
</TR>
</table>
<?php } else { echo '___aac0'; } ?>