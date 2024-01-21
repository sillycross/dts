<?php

define('CURSCRIPT', 'kuji');

require './include/common.inc.php';

$_REQUEST = gstrfilter($_REQUEST);
$ktype=$_REQUEST['type'];
if ($ktype=="") $ktype=0;
if (isset($_POST['choice']))
{
	$choice=(int)$_POST['choice'];
	if ($choice<1 || $choice>4) $choice=0;
}
else  $choice=0;

$udata = udata_check();

extract($udata);

$userCardData = \cardbase\get_user_cardinfo($cuser);
$oc = $userCardData['cardlist'];

//刷新卡包cardindex
\cardbase\parse_card_index();

if ($ktype==1 || $choice>0 || !empty($packchoice))
{
	eval(import_module('kujibase'));
	
	$kreq=$kujicost;
	
	$kres=\cardbase\kuji($ktype,$udata);

  //抽卡成功的显示
	if (is_array($kres)){
		if ($ktype==0 || $ktype==2)	//单抽4选1生成其他3张假结果
		{
			$t=Array(); $tmp=Array();
			for ($i=1; $i<=4; $i++)
			{
				if ($i==$choice)
					$t[$i]=$kres[0];
				else	$t[$i]=\cardbase\kuji($ktype,$tmp,true)[0];
			}
			$kres=$t;
		}
		$isnew=array();
		//单抽、8抽4的高亮显示
		$ishighlight=Array();
		if ($ktype==0 || $ktype==2) 
		{
			for ($i=1; $i<=4; $i++) $ishighlight[$i]=($i==$choice);
		}
		elseif($kujinum_in_pack[$ktype] > 0) {
			//8抽4结果倒序排列
			$kres = array_reverse($kres);
			//卡包内的高亮显示。注意由于倒序排列，这里是倒着来的
			for ($i=$kujinum[$ktype]; $i>$kujinum[$ktype]-$kujinum_in_pack[$ktype]; $i--) {
				$ishighlight[$i-1]=1;
			}
		}
		//把卡片编号和镜碎等级分离
		$isblink = Array();
		foreach($kres as $key => $val) {
			if(strpos($val, '_')!==false){
				list($kres[$key], $isblink[$key]) = explode('_', $val);
			}
		}
		//判定哪些卡是新获得的卡
		foreach($kres as $key => $val){
			if (($ktype==0 || $ktype==2) && $choice!=$key)	//单抽没有真正获得的卡不显示new字样
			{
				$isnew[$key]=0; continue;
			}
			if (!in_array($val,$oc)){
				$isnew[$key]=1;
			}elseif ((!empty($isblink[$key]) ? $isblink[$key] : 0) > (!empty($userCardData['card_data'][$val]['blink']) ? $userCardData['card_data'][$val]['blink'] : 0)){
				$isnew[$key]=2;
			}else{
				$isnew[$key]=0;
			}
		}
		include template('kujiresult');
	}else{
		gexit($_ERROR['kuji_failure'], __file__, __line__);
	}
}
else
{
	//翻卡UI
	include template('kujipick');
}
?> 