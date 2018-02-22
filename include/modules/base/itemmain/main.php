<?php

namespace itemmain
{
	function init() 
	{
		eval(import_module('player'));
		global $item_equip_list;
		$equip_list=array_merge($equip_list,$item_equip_list);
	}
	
	//1:一般可合并道具  2:食物  0:不可合并
	function check_mergable($ik){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(preg_match('/^(WC|WD|WF|Y|B|C|TN|GA|GB|M|V|ygo|fy|p)/',$ik)) return 1;
		elseif(preg_match('/^(H|P)/',$ik)) return 2;
		else return 0;
	}
	
	//省略显示
	//显示宽度20英文字符，假设汉字的显示宽度大约是英文字母的1.8倍
	function parse_itmname_words($name_value, $elli = 0, $width=20, $end=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$elli) return $name_value;
		
		if($width<=6) $width = 6;
		$ilen=mb_strlen($name_value);
		$slen=0;
		for($i=0;$i<$ilen;$i++){
			$c=mb_substr($name_value,$i,1);
			if(strlen($c) > mb_strlen($c)) $slen+=1.8;//是汉字或别的UTF-8字符，显示宽度+1.8
			else $slen+=1;//是英文字母或其他ascii字符，显示宽度+1
			if($slen >= $width) break;
		}
		if($i==$ilen) return $name_value;
		else return middle_abbr($name_value,$i-1,$end);
	}
	
	function parse_itmk_words($k_value, $reveal=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('itemmain'));
		if($k_value){	
			$best=-1;
			$ret='未知';
			foreach($iteminfo as $info_key => $info_value)
			{
				if(strpos($k_value,$info_key)===0){
					if (strlen($info_key)>$best)
					{
						$best=strlen($info_key);
						$ret = $info_value;
					}
				}	
			}
		} else {
			$ret = '';
		}
		return $ret;
	}
	
	function parse_itmk_desc($k_value, $sk_value) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return '';
	}
	
	function count_itmsk_num($sk_value)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$sk_arr = get_itmsk_array($sk_value);
		return count($sk_arr);
//		$ret=0;
//		for ($i=0; $i<strlen($sk_value); $i++)
//		{
//			if ('a'<=$sk_value[$i] && $sk_value[$i]<='z') $ret+=1;
//			if ('A'<=$sk_value[$i] && $sk_value[$i]<='Z') $ret+=1;
//			if ($sk_value[$i]=='^') $ret+=1;
//		}
//		//$ret/=2; $ret=(int)$ret;
//		return $ret;
	}
	
	//鉴于字母已经基本用完，新属性应该全部命名为“^数字”的形式，其中数字可以任意
	//例： ^233 => '防拳' 
	function get_itmsk_array($sk_value)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		$i = 0;
		while ($i < strlen($sk_value))
		{
			$sub = substr($sk_value,$i,1); 
			$i++;
			if(!empty($sub) && !in_array($sub, array('|'))){
				if ($sub=='^')
				{
					//$flag = 1;
					while ($i<strlen($sk_value) && '0'<=$sk_value[$i] && $sk_value[$i]<='9') 
					{
						$sub.=$sk_value[$i];
						$i++;
					}
//					if ($i<strlen($sk_value) && $sk_value[$i]=='^')
//					{
//						$sub.='^'; $i++;
//					}
//					else  continue;
				}
				array_push($ret,$sub);
			}					
		}
		//if(!empty($flag)) var_dump($ret);
		return $ret;		
	}
	
	function parse_itmsk_words($sk_value, $simple = 0, $elli = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('itemmain'));
		//纯数字或者以等号开头的，也认为是空的（特殊用法）
		if($sk_value && is_numeric($sk_value) === false && strpos($sk_value,'=')!==0){
			$ret = '';
			$sk_arr = get_itmsk_array($sk_value);
			$i = $elli_aready = 0;
			$imax = count($sk_arr);
			if(!empty($sk_arr)){
				$got = array();//除天然和奇迹外的同种属性只显示1次
				foreach($sk_arr as $sv){
					if(!in_array($sv,$got)){
						if(!$i){
							$ret .= $itemspkinfo[$sv];
						}elseif($elli && $i >= 3 && $i < $imax-1){
							if(!$elli_aready){
								$ret .= '+…';
								$elli_aready = 1;
							}
						}else{
							$ret .= '+'.$itemspkinfo[$sv];
						}
					}
					$i ++ ;
					if('z'!=$sv && 'x'!=$sv) $got[] = $sv;
				}
				//$ret = substr($ret,0,-1);
			}
		} else {
			if ($simple)
				$ret='';
			else  $ret = $nospk;
		}
		return $ret;
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return '';
	}
	
	function parse_itmsk_desc($sk_value){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		$ret = '';
		if($sk_value && is_numeric($sk_value) === false && strpos($sk_value,'=')!==0){
			$i = 0;
			$sk_arr = get_itmsk_array($sk_value);
			if(!empty($sk_arr)){
				foreach($sk_arr as $sv){
					$ret .= $itemspkinfo[$sv].'：'.$itemspkdesc[$sv].'<br>';
				}
				$ret = substr($ret,0,-4);
			}
		}
		return $ret;
	}
	
	//把身上装备道具的显示信息全部处理一遍
	//$elli=1时自动省略超过10个字的道具名的中间部分
	//$simple=1时无属性直接返回空
	function parse_item_words($edata, $simple = 0, $elli = 0)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','itemmain'));
		$r=Array();
		
		foreach ($equip_list as $v) {
			$z=strlen($v)-1;
			while ('0'<=$v[$z] && $v[$z]<='9') $z--;//注意这同样也会把wep等包括进去！
			$p1 = substr($v,0,$z+1); $p2 = substr($v,$z+1);
			$kv=$p1.'k'.$p2;
			$ev=$p1.'e'.$p2;
			$sv=$p1.'s'.$p2;
			$skv=$p1.'sk'.$p2;
			$r[$v.'_words'] = parse_itmname_words($edata[$v], $elli);
			$r[$kv.'_words'] = parse_itmk_words($edata[$kv]);
			$r[$kv.'_desc'] = parse_itmk_desc($edata[$kv],$edata[$skv]);
			$r[$skv.'_words'] = parse_itmsk_words($edata[$skv], $simple, $elli);
			$r[$skv.'_desc'] = parse_itmsk_desc($edata[$skv]);
			$itmuse_words = parse_itmuse_desc($edata[$v], $edata[$kv], $edata[$ev], $edata[$sv], $edata[$skv]);
			$r[$v.'_itmuse_desc'] = $itmuse_words;
		}
		
		return $r;
		
	}
	
	function parse_interface_profile()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		\player\update_sdata();
		$tpldata+=parse_item_words($sdata,0,1);
		$chprocess();
	}
	
	function rs_game($xmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys','map','itemmain'));
		if ($xmode & 16) {	//地图道具初始化
			$plsnum = sizeof($plsinfo);
			$iqry = '';
			$itemlist = get_itemfilecont();
			$in = sizeof($itemlist);
			$an = $areanum ? ceil($areanum/$areaadd) : 0;
			for($i = 1; $i < $in; $i++) {
				if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false){
					list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = mapitem_data_process(explode(',',$itemlist[$i]));
					if(strpos($iskind,'=')===0){
						$tmp_pa_name = substr($iskind,1);
						$iskind = '';
						$result = $db->query("SELECT pid FROM {$tablepre}players WHERE name='$tmp_pa_name' AND type>0");
						if($db->num_rows($result)){
							$ipid = $db->fetch_array($result);
							$iskind = $ipid['pid'];
						}
					}
					if( $iarea == $an || $iarea == 99 || ($iarea == 98 && $an > 0)) {
						for($j = $inum; $j>0; $j--) {
							if ($imap == 99)
							{
								do {
									$rmap = rand(0,$plsnum-1);
								} while (in_array($rmap,$map_noitemdrop_arealist));
							}
							else  $rmap = $imap;
							$iqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$rmap'),";
						}
					}
				}
			}
			if(!empty($iqry)){
				$iqry = "INSERT INTO {$tablepre}mapitem (itm,itmk,itme,itms,itmsk,pls) VALUES ".substr($iqry, 0, -1);
				$db->query($iqry);
			}
		}
	}
	
	//某些模式特殊处理数据
	function mapitem_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return $data;
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$file = __DIR__.'/config/mapitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$file = __DIR__.'/config/stitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$file = __DIR__.'/config/stwep.config.php';
		$l = openfile($file);
		return $l;
	}
	
	function discover_item()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','itemmain'));
		
		$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls'");
		$itemnum = $db->num_rows($result);
		if($itemnum <= 0){
			$log .= '<span class="yellow">周围找不到任何物品。</span><br>';
			$mode = 'command';
			return;
		}
		$itemno = rand(0,$itemnum-1);
		$db->data_seek($result,$itemno);
		$mi=$db->fetch_array($result);
		$itms0 = focus_item($mi);
		if($itms0){
			itemfind();
			return;
		} else {
			$log .= "但是什么都没有发现。<br>";
		}
		$mode = 'command';
	}
	
	function focus_item($iarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(isset($iarr['iid'])){
			$iid = $iarr['iid'];
			$db->query("DELETE FROM {$tablepre}mapitem WHERE iid='$iid'");
			$itm0=$iarr['itm'];
			$itmk0=$iarr['itmk'];
			$itme0=$iarr['itme'];
			$itms0=$iarr['itms'];
			$itmsk0=$iarr['itmsk'];
			return $itms0;
		}
		return false;
	}
	
	function calculate_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		return $item_obbs;
	}
	
	function calculate_itemfind_obbs_multiplier()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	function discover($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//echo 'itemmain ';
		$find_obbs = calculate_itemfind_obbs()*calculate_itemfind_obbs_multiplier();
		$dice = rand(0,99);
		if($dice < $find_obbs) {
			discover_item();
			return;
		}
		$chprocess($schmode);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input'));
		if ($mode == 'command' && strpos($command,'itm') === 0) 
		{
			$item = substr($command,3);
			itemuse_wrapper($item);
			return;
		} 
		if ($mode == 'command' && $command == 'itemmain' && 
			($itemcmd=='itemmerge' || $itemcmd=='itemmove' || $itemcmd=='itemdrop'))
		{
			ob_clean();
			if ($itemcmd=='itemmerge') include template(MOD_ITEMMAIN_ITEMMERGE);
			elseif ($itemcmd=='itemmove') include template(MOD_ITEMMAIN_ITEMMOVE);
			elseif ($itemcmd=='itemdrop') include template(MOD_ITEMMAIN_ITEMDROP);
			$cmd = ob_get_contents();
			ob_clean();
		}
		if($mode == 'itemmain') {
			if($command == 'itemget') {
				itemget();
			} elseif($command == 'itemadd') {
				itemadd();
			} elseif($command == 'itemmerge') {
				if($merge2 == 'n'){itemadd();}
				else{
					$merge_ret = itemmerge($merge1,$merge2);
					if(!$merge_ret && ${'itm'.$merge1} != ${'itm'.$merge2}) {
						eval(import_module('logger'));
						$log .= '<br>系统将你的命令自动识别为道具移动。';
						itemmove($merge1,$merge2);
					}
				}
			} elseif($command == 'itemmove') {
				itemmove($from,$to);
			} elseif(strpos($command,'drop') === 0) {
				$drop_item = substr($command,4);
				itemdrop($drop_item);
			} elseif(strpos($command,'off') === 0) {
				$off_item = substr($command,3);
				itemoff($off_item);
			} elseif(strpos($command,'swap') === 0) {
				$swap_item = substr($command,4);
				itemdrop($swap_item);
				if(strpos($swap_item,'itm')===0) itemadd();
				//如果要允许直接换上拾取的装备，请取消此行注释
				//else itemuse_wrapper(0);
			} 
		}
		$chprocess();
	}
}

?>
