<?php

namespace itemmix
{
	$mix_type = array('normal' => '');
	
	function init() {}
	
	//获得$mixinfo的函数，要往数组里添加临时合成可以继承这里。注意继承的时候不要import同名变量进来导致又污染了。
	function get_mixinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmix'));
		return $mixinfo;
	}
	
	//合成成功时的提示以及道具的获得，要进行其他处理的功能可以继承这里
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		$itmstr = $uip['itmstr'];
		
		//“通常”合成当动词实在是太奇怪了
		$tpstr = (empty($uip['mixtp']) || $uip['mixtp']==$mix_type['normal']) ? '' : $mix_type[$uip['mixtp']];
		
		$log .= "<span class=\"yellow b\">$itmstr</span>{$tpstr}合成了<span class=\"yellow b\">{$itm0}</span><br>";
		addnews($now,'itemmix',$name,$itm0,$tpstr);
	
		$wd+=1;
		if((strpos($itmk0,'H') === 0)&&($club == 16)&&($itms0 !== $nosta)){ $itms0 = ceil($itms0*2); }
		elseif(($itmk0 == 'EE' || $itmk0 == 'ER') && ($club == 7)){ $itme0 *= 5; }

		\itemmain\itemget();
	}
	
	function itemmix_place_check($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		$mlist2 = array_unique($mlist);	
		if(count($mlist) != count($mlist2)) {
			$log .= '相同道具不能进行合成！<br>';
			$mode = 'itemmix';
			return false;
		}
		if(count($mlist) < 2){
			$log .= '至少需要2个道具才能进行合成！';
			$mode = 'itemmix';
			return false;
		}
		
		foreach($mlist as $val){
			if(!${'itm'.$val}){
				$log .= '所选择的道具不存在！';
				$mode = 'itemmix';
				return false;
			}
		}
		return true;
	}
	
	function itemmix_name_proc($n){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$n = trim($n);
		foreach(Array($itmname_ignore) as $value){
			$n = preg_replace($value,'',$n);
		}
		if(strpos($n, '小黄的')!==false) $n = preg_replace('/\[\+[0-9]+?\]/si','',$n);//小黄强化特判可以合成
		$n = str_replace('钉棍棒','棍棒',$n);
		return $n;
	}
	
	//查看哪些合成公式符合要求
	//$mi已改为道具数组
	function itemmix_recipe_check($mixitem){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$res = array();
		if(count($mixitem) >= 2){
			$recipe = get_mixinfo();
			$mi_names = array();
			foreach($mixitem as $i) $mi_names[] = itemmix_name_proc($i['itm']);
			sort($mi_names);
			foreach($recipe as $minfo){
				$ms = $minfo['stuff'];
				sort($ms);
				if(count($mi_names)==count($ms) && $mi_names == $ms) {
					$minfo['type'] = 'normal';
					$res[] = $minfo;
				}
			}
		}
		return $res;	
	}
	
	function parse_itemmix_resultshow($rarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ret = $rarr[0].'/'.\itemmain\parse_itmk_words($rarr[1],1).'/'.$rarr[2].'/'.$rarr[3];
		$itmskw = !empty($rarr[4]) ? \itemmain\parse_itmsk_words($rarr[4],1) : '';
		if($itmskw) $ret .= '/'.$itmskw;
		return $ret;
	}
	
	function itemmix_option_show($mix_res)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		ob_start();
		include template(MOD_ITEMMIX_ITEMMIX_OPTIONS);
		$res = ob_get_contents();
		ob_end_clean();
		return $res;
	}
	
	//用户界面暂存的合成素材列表
	function calc_mixmask($mlist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$mask=0;
			foreach($mlist as $k)
				if ($k>=1 && $k<=6)
					$mask|=(1<<((int)$k-1));
		return $mask;
	}
	
	function itemmix_get_result($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$mixitem = array();
		foreach($mlist as $val){
			$mixitem[$val] = array(
				'itm' => ${'itm'.$val},
				'itmk' => ${'itmk'.$val},
				'itme' => ${'itme'.$val},
				'itms' => ${'itms'.$val},
				'itmsk' => ${'itmsk'.$val},
			);
		}
		return itemmix_recipe_check($mixitem);
	}
	
	function itemmix($mlist, $itemselect=-1) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		
		if(!itemmix_place_check($mlist)) return;
		
		$mix_res = itemmix_get_result($mlist);
		
		$mixitemname = array();
		foreach($mlist as $val) $mixitemname[] = ${'itm'.$val};
		$uip['itmstr'] = implode(' ', $mixitemname);
		$uip['mixmask'] = calc_mixmask($mlist);
		if(!$mix_res) {//没有合成选项
			$log .= "<span class=\"yellow b\">{$uip['itmstr']}</span>不能合成！<br>";
			ob_clean();
			include template(get_itemmix_filename());
			$cmd = ob_get_contents();
			ob_clean();
		} elseif(count($mix_res) > 1) {//合成选项2个以上
			if($itemselect >= 0) {//有选择则合成
				itemmix_proc($mlist, $mix_res[$itemselect]);
			}else{//否则显示合成选项
				$cmd.=itemmix_option_show($mix_res);
				$uip['itemmix_option_show'] = 1;
			}
		} else {//只有1个合成选项则直接合成
			itemmix_proc($mlist, $mix_res[0]);
		}
		return;
	}
	
	//执行合成
	function itemmix_proc($mlist, $minfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		foreach($mlist as $val){
			itemmix_reduce('itm'.$val);
		}
		$itm0 = $minfo['result'][0];
		$itmk0 = $minfo['result'][1];
		$itme0 = $minfo['result'][2];
		$itms0 = $minfo['result'][3];
		if (isset($minfo['result'][4]))
			$itmsk0 = $minfo['result'][4];
		else{
			$itmsk0 = '';
		}
		$uip['mixcls'] = !empty($minfo['class']) ? $minfo['class'] : '';
		$uip['mixtp'] = $minfo['type'];
		itemmix_success();
	}
	
	function get_itemmix_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		return MOD_ITEMMIX_ITEMMIX;
	}
	
	function itemmix_reduce($item){ //只限合成使用！！
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
		} else {
			return;
		}

		if(!$itms) { return; }
		if($itms !== $nosta && preg_match('/^(Y|B|C|X|TN|GB|H|P|V|M|R)/',$itmk)){
			$itms--;
		}
		else{
			$itms=0;
		}
		if($itms <= 0) {
			$itms = 0;
			$log .= "<span class=\"red b\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$itemcmd = get_var_input('itemcmd');
		if($mode == 'itemmain') {
			if($command == 'itemmix') {
				//$itemselect是有二级选项时的玩家所选项，同时$mixmask是以位运算方式储存的合成素材序号
				list($itemselect, $mixmask) = get_var_input('itemselect', 'mixmask');
				if (999 == $itemselect)
					$mode='command';
				else
				{
					$mixlist = array();
					if (NULL === $mixmask)
					{
						for($i=1;$i<=6;$i++)
							if(!empty(get_var_input('mitm'.$i)))
								$mixlist[] = $i;
					}
					else
					{
						for($i=1;$i<=6;$i++)
							if ($mixmask&(1<<($i-1)))
								$mixlist[] = $i;
					}
					if (NULL !== $itemselect)
						itemmix($mixlist,$itemselect);//二级选项
					else itemmix($mixlist);//直接合成
					
					if(!empty($uip['itemmix_option_show'])) {
						ob_start();
						//显示合成选项表的头尾
						include template(MOD_ITEMMIX_ITEMMIX_OPTION_START);
						echo $cmd;
						include template(MOD_ITEMMIX_ITEMMIX_OPTION_END);
						$cmd = ob_get_contents();
						ob_end_clean();
					}
				}
			}
		}
		elseif ($mode == 'command' && $command == 'itemmain' && $itemcmd=='itemmix')
		{
			eval(import_module('logger'));
			$log .= '你想要合成什么？';
			ob_clean();
			include template(get_itemmix_filename());
			$cmd = ob_get_contents();
			ob_clean();
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'itemmix') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}{$c}合成了{$b}</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}

}

?>