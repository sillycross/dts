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
		if(preg_match('/^(WC|WD|WF|Y|B|C|EI|TN|G|M|V|ygo|fy|p)/',$ik) && !preg_match('/^W[A-Z][A-Z]/',$ik)) return 1;
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
	
	//道具效和耐的省略显示
	function parse_itmnum_words($num_value, $elli = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$elli || !is_numeric($num_value)) return $num_value;
		$ret = $num_value;
		if($num_value > 100000000) {
			if(round($num_value/10000000)%10) $ret = round($num_value/10000000)/10;
			else $ret = round($num_value/100000000);
			$ret .= '亿';
		}elseif($num_value > 10000) {
			if(round($num_value/1000)%10) $ret = round($num_value/1000)/10;
			else $ret = round($num_value/10000);
			$ret .= '万';
		}
		return $ret;
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
	
	//属性格式有3种：
	//1. 单个字母
	//2. ^数字
	//3. ^字母数字，必须以数字结尾
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
					$flag = 1;
					while ($i<strlen($sk_value)) 
					{
						//^后，出现数字以后，遇到第一个不是数字的字符时跳出
						if('0'<=$sk_value[$i] && $sk_value[$i]<='9') $flag = 0;
						elseif(!$flag) break;
						$sub.=$sk_value[$i];
						$i++;
					}
				}
				array_push($ret,$sub);
			}
		}
		//if(!empty($flag)) var_dump($ret);
		return $ret;		
	}
	
	//获得复合属性的代号和数值。非复合属性返回NULL
	function get_comp_itmsk_info($str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!is_string($str) || '^' != $str[0]) return NULL;
		$skk = $skn = '';
		for($i=1;$i<strlen($str);$i++) {
			if('0'<=$str[$i] && $str[$i]<='9') {
				if(empty($skk)) return NULL;
				else $skn .= $str[$i];
			}else{
				$skk .= $str[$i];
			}
		}
		$skk = '^'.$skk;
		return array($skk, $skn);
	}
	
	function get_itmsk_words_single($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		$cinfo = get_comp_itmsk_info($str);
		if(!empty($cinfo)) return $itemspkinfo[$cinfo[0]];
		elseif(!empty($itemspkinfo[$str])) return $itemspkinfo[$str];
		else return '';
	}
	
	function get_itmsk_desc_single($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		$cinfo = get_comp_itmsk_info($str);
		if(!empty($cinfo)) {
			$desc = $itemspkdesc[$cinfo[0]];
			$desc = str_replace('<:skn:>', get_itmsk_desc_single_comp_process($cinfo[0], $cinfo[1]), $desc);
			return $desc;
		}elseif(!empty($itemspkdesc[$str])) return $itemspkdesc[$str];
		else return '';
	}
	
	function get_itmsk_desc_single_comp_process($skk, $skn) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $skn;
	}
	
	function parse_itmsk_words($sk_value, $simple = 0, $elli = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
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
						$skw = get_itmsk_words_single($sv);
						if(!$i){
							$ret .= $skw;
						}elseif($elli && $i >= 3 && $i < $imax-1){
							if(!$elli_aready){
								$ret .= '+…';
								$elli_aready = 1;
							}
						}else{
							$ret .= '+'.$skw;
						}
					}
					$i ++ ;
					if('z'!=$sv && 'x'!=$sv) $got[] = $sv;
				}
				//$ret = substr($ret,0,-1);
			}
		} else {
			eval(import_module('itemmain'));
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
		$ret = '';
		if($sk_value && is_numeric($sk_value) === false && strpos($sk_value,'=')!==0){
			$i = 0;
			$sk_arr = get_itmsk_array($sk_value);
			if(!empty($sk_arr)){
				foreach($sk_arr as $sv){
					$skw = get_itmsk_words_single($sv);
					$ret .= $skw.'：'.get_itmsk_desc_single($sv).'<br>';
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
			$r[$v.'_words'] = parse_itmname_words($edata[$v], $elli);//这里如果$elli==0则会省略到20个字符
			$r[$v.'_words_short'] = parse_itmname_words($edata[$v], 1, 15);//常用到的一个省略
			$r[$kv.'_words'] = parse_itmk_words($edata[$kv]);
			$r[$ev.'_words'] = parse_itmnum_words($edata[$ev], $elli);
			$r[$sv.'_words'] = parse_itmnum_words($edata[$sv], $elli);
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
		if(empty($tpldata['itm1_words']))
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
					if( $iarea == $an || $iarea == 99 || ($iarea == 98 && $an > 0)) {
						for($j = $inum; $j>0; $j--) {
							if ($imap == 99)
							{
								do {
									$rmap = rand(0,$plsnum-1);
								} while (in_array($rmap,$map_noitemdrop_arealist));
							}
							else  $rmap = $imap;
							list($iname, $ikind, $ieff, $ista, $iskind, $rmap) = mapitem_single_data_process($iname, $ikind, $ieff, $ista, $iskind, $rmap);
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
	
	//判断道具类型是否可以装备的小函数，主要用于显示
	function is_equipable($itmkstr){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('itemmain'));
		$equipable = false;
		foreach($itemkind_equipable as $val){
			if(strpos($itmkstr,$val)===0) {
				$equipable = true;
				break;
			}
		}
		return $equipable;
	}
	
	//同名道具的data处理
	//天然带毒物品的NPC pid自动处理
	//也用于某些模式特殊处理数据
	function mapitem_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		if(!empty($data[7])){
			$iskind = $data[7];
			if(strpos($iskind,'=')===0){
				eval(import_module('sys'));
				$tmp_pa_name = substr($iskind,1);
				$iskind = '';
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE name='$tmp_pa_name' AND type>0");
				if($db->num_rows($result)){
					$iskind = $db->fetch_array($result)['pid'];
				}
				$data[7] = $iskind;
			}
		}
		return $data;
	}
	
	//每个刷新到地图上的道具的data处理
	function mapitem_single_data_process($iname, $ikind, $ieff, $ista, $iskind, $imap){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return array($iname, $ikind, $ieff, $ista, $iskind, $imap);
	}
	
	//跟道具有关的几个配置文件的读取
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
	
	//初始化开局装备道具，仅在valid流程调用，也非引用而是直接传回数组
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp['arb'] = $ebp['gd'] == 'm' ? '男生校服' : '女生校服';
		$ebp['arbk'] = 'DB'; $ebp['arbe'] = 5; $ebp['arbs'] = 15;
		$ebp['itm1'] = '面包'; $ebp['itmk1'] = 'HH'; $ebp['itme1'] = 100; $ebp['itms1'] = 30;
		$ebp['itm2'] = '矿泉水'; $ebp['itmk2'] = 'HS'; $ebp['itme2'] = 100; $ebp['itms2'] = 30;
		
		$weplist = get_startingwepfilecont();
		do { 
			$index = rand(1,count($weplist)-1); 
			list($ebp['wep'],$ebp['wepk'],$ebp['wepe'],$ebp['weps'],$ebp['wepsk']) = explode(",",$weplist[$index]);
		} while(!$ebp['wepk']);

		$stitemlist = get_startingitemfilecont();
		for($i=3;$i<=4;$i++){
			do { 
				$index = rand(1,count($stitemlist)-1); 
				list($ebp['itm'.$i],$ebp['itmk'.$i],$ebp['itme'.$i],$ebp['itms'.$i],$ebp['itmsk'.$i]) = explode(",",$stitemlist[$index]);
			} while(!$ebp['itms'.$i]);
		}
		return $ebp;
	}
	
	//探索道具主过程
	//返回是否探索到道具
	function discover_item()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','itemmain'));
		
		$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls'");
		$itemnum = $db->num_rows($result);
		if($itemnum <= 0){
			$log .= '<span class="yellow b">周围找不到任何物品。</span><br>';
			$mode = 'command';
			return false;
		}
		$mipool = Array();
		//从数据库一口气拉取当前地图所有道具
		while($r = $db->fetch_array($result)){
			if(discover_item_filter($r))
				$mipool[] = $r;
		}
		//打乱数组，相当于随机取一个
		shuffle($mipool);
		$mi = $mipool[0];
		
		$itms0 = focus_item($mi);
		if($itms0){
			itemfind();
			return true;
		} else {
			$log .= "但是什么都没有发现。<br>";
		}
		$mode = 'command';
		return false;
	}
	
	//在读取数据库时就过滤掉不符合条件的道具
	//传入$iarr为从数据库fetch的单条道具数组
	//返回$ret为boll
	function discover_item_filter($iarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}
	
	//拾取道具的过程，传入一个包含道具在数据库中id的数组，删除数据库对应行，并把对应数据放入player数据的0号道具位
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
	
	//计算道具发现率
	function calculate_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		return $item_obbs;
	}
	
	//计算道具发现率的因子
	function calculate_itemfind_obbs_multiplier()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	//继承发现主过程
	function discover($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//echo 'itemmain ';
		$find_obbs = calculate_itemfind_obbs()*calculate_itemfind_obbs_multiplier();
		$dice = rand(0,99);
		if($dice < $find_obbs) {
			return discover_item();
		}
		return $chprocess($schmode);
	}
	
	function pre_act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input'));
		//在手里有道具的情况下阻止意料之外的指令，防止道具被洗掉
		//如果有模块在这之前执行并且获得道具那就没办法了……
		if(!empty($hp) && !empty($itms0) && !in_array($command, Array('itm0','dropitm0','itemget','itemmerge','enter')) && false === strpos($command, 'swap')){
			eval(import_module('logger'));
			$log .= '你的双手都已经抓满了东西。为了完成所想，你集中意念召唤幻肢……<br>什么都没有发生，除了你的脑壳痛了起来。<br><br>';
			$mode = 'command';
			$command='menu';
		}
		$chprocess();
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
				itemget_process();
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
				if(strpos($swap_item,'itm')===0) {
					if(${str_replace('itm','itms',$swap_item)}) itemdrop($swap_item);
					itemadd(substr($swap_item,3,1));
				}
			} 
		}
		$chprocess();
	}
}

?>
