<?php

namespace itemmain
{
	$tmp_itmsk_arr_pool = Array();//道具属性字符串转数组的临时池子，减少反复判定道具属性的开销
	
	function init() 
	{
		eval(import_module('player'));
		global $item_equip_list;
		$equip_list=array_merge($equip_list,$item_equip_list);
	}
	
	//1:一般可合并道具  2:食物  0:不可合并
	function check_mergable($ik){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(preg_match('/^(WC|WD|WF|Y|B|C|EI|TN|G|M|V|ygo|fy|p|EA)/',$ik) && !preg_match('/^W[A-Z][A-Z]/',$ik)) return 1;
		elseif(preg_match('/^(H|P)/',$ik)) return 2;
		else return 0;
	}
	
	//道具名处理
	//会自动进行道具省略显示的转换
	function parse_itmname_words_shell($name_value, &$pa=NULL, $elli = 0, $width=20, $end=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return parse_itmname_words($name_value, $elli, $width, $end);
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
	
	//$ignore_invisible表示是否忽略不显示的复合属性，默认不忽略，判定在attrbase模块中
	function count_itmsk_num($sk_value, $ignore_invisible = 1)
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
	//1、单字母或者符号，如A=>物防。目前可用字符几乎已经用完，不建议继续增加。另外下划线_和分隔符|另有他用。
	//2、^数字。如^001=>同调。
	//3、^字母数字。如^dd20=>降防20%。注意：就算效果和数字无关，使用时也必须以数字结尾。
	//此外，第三项在字母后加下划线，可以通过get_comp_itmsk_info($itmsk)获取下划线后面至数字之间的内容，具体见attrbase模块
	//如果$not_ignore传入真值，会把竖线等本应被忽略的字符也当做属性返回，用于一些需要严格判断的场合。
	function get_itmsk_array($sk_value, $not_ignore = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('itemmain'));
		//如果之前判定过完全相同的属性就直接用，避免多次性能开销。注意如果$not_ignore开启，不能使用这个功能
		if(!empty($tmp_itmsk_arr_pool[$sk_value]) && !$not_ignore) {
			//echo $sk_value.' ';
			return $tmp_itmsk_arr_pool[$sk_value];
		}
		
		$ret = Array();
		$i = 0;
		while ($i < strlen($sk_value))
		{
			$sub = substr($sk_value,$i,1); 
			$i++;
			if(!empty($sub) && ($not_ignore || !in_array($sub, array('|')))){
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
		$tmp_itmsk_arr_pool[$sk_value] = $ret;
		
		return $ret;		
	}
	
	//判定一个属性数组里是不是有给定的属性代号
	//输入$mark是用来判定的单字母属性或者以^字母为形式的复合属性前缀
	//输入$skarr是已经经过get_itmsk_array()处理的属性数组，直接输入属性字符串也会自动转换
	//如果$count==1，则会统计属性总数。
	//如果属性不存在，返回false；存在则返回1；如果是复合属性或者要统计总数，会返回具体数值；如果有记录特殊变量，则返回特殊变量。
	
	//这里还不涉及复合属性的判定，实际处理在attrbase模块里完成，请注意	
	function check_in_itmsk($mark, $skarr, $count = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!is_array($skarr)) $skarr = get_itmsk_array($skarr);
		
		$flag = false;
		if(in_array($mark, $skarr)) {
			if(!$count) $flag = true;
			else {
				$flag = 0;
				foreach($skarr as $v){
					if($v === $mark) $flag ++;
				}
			}
		} 
		
		return $flag;
	}
	
	//从属性字符串中替换单个属性。注意不会检测$replacement是否合法
	function replace_in_itmsk($needle, $replacement, $itmsk, $dummy)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//这里只处理单个属性或者数字编号属性，直接str_replace()。对复合属性的处理在attrbase模块
		return str_replace($needle, $replacement, $itmsk);
	}
	
	function get_itmsk_words_single($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		if(!empty($itemspkinfo[$str])) return $itemspkinfo[$str];
		return '';
	}
	
	function get_itmsk_desc_single($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		if(!empty($itemspkdesc[$str])) return $itemspkdesc[$str];
		return '';
	}
	
	//在显示界面把换行符注释变成真的换行符，需要手动调用
	function replace_crlf($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return str_replace('<!--CRLF-->','<BR />',$str);
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
						if(empty($skw)) continue;
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
					if(empty($skw)) continue;
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
			$r[$v.'_words'] = parse_itmname_words_shell($edata[$v], $edata, $elli);//这里如果$elli==0则会省略到20个字符
			$r[$v.'_words_short'] = parse_itmname_words_shell($edata[$v], $edata, 1, 15);//常用到的一个省略
			$r[$v.'_words_noelli'] = parse_itmname_words_shell($edata[$v], $edata, 0);//要求不省略但是进行其他处理时
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
	
	//游戏开局或者过禁时，刷地图道具
	function rs_game($xmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys','map','itemmain'));
		if ($xmode & 16) {	//地图道具初始化
			lay_mapitem();
		}
	}

	//在地图上刷新道具的主要函数
	function lay_mapitem($lpls = -1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','itemmain'));
		$plsnum = \map\get_plsnum();
		$iqry = '';
		$itemlist = get_itemfilecont();
		$itemlist = itemlist_data_process($itemlist);
		$in = sizeof($itemlist);
		$an = \map\get_area_wavenum();
		for($i = 1; $i < $in; $i++) {
			if(!empty($itemlist[$i]) && substr($itemlist[$i], 0, 1) != '=' && strpos($itemlist[$i],',')!==false){//跳过空行和注释行（没有逗号的行）
				list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = mapitem_row_data_seperate($itemlist[$i]);
				if( $iarea == $an || $iarea == 99 || ($iarea == 98 && $an > 0)) {//禁区判定，99为每禁，98为一禁后每禁
					if($lpls == -1 || $lpls == $imap){//地图判定，-1为不限制（刷所有固定道具和全图随机道具），99为全图随机
						for($j = $inum; $j>0; $j--) {
							if ($imap == 99)
							{
								do {
									$rmap = rand(0,$plsnum-1);
								} while (in_array($rmap,$map_noitemdrop_arealist));
							}
							else  $rmap = $imap;
							list($iname, $ikind, $ieff, $ista, $iskind, $rmap) = mapitem_single_data_attr_process($iname, $ikind, $ieff, $ista, $iskind, $rmap);
							$iqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$rmap'),";
						}
					}
				}
			}
		}
		if(!empty($iqry)){
			$iqry = "INSERT INTO {$tablepre}mapitem (itm,itmk,itme,itms,itmsk,pls) VALUES ".substr($iqry, 0, -1);
			$db->query($iqry);
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
	
	//整个mapitem文件转成数组后的数据处理
	//本模块里是直接返回，其他模块需要对数据做整体修改的请继承这个函数
	function itemlist_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return $data;
	}

	//单行mapitem记录的分割处理
	//本模块是explode后调用mapitem_row_data_process()处理
	function mapitem_row_data_seperate($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return mapitem_row_data_process(explode(',',$data));
	}
	
	//单条mapitem记录的data处理
	//天然带毒物品的NPC pid自动处理
	//也用于某些模式特殊处理数据
	function mapitem_row_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		if(!empty($data[7]) && strpos($data[7],'=')===0){//如果属性以=开头，认为后面是NPC的名字
			$isk = & $data[7];
			eval(import_module('sys'));
			$tmp_pa_name = substr($isk,1);
			$isk = '';//无论有没有对应的NPC存在，先把data的属性写成空的
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE name='$tmp_pa_name' AND type>0");
			if($db->num_rows($result)){
				$npcid = $db->fetch_array($result)['pid'];
			}
			if(!empty($npcid)) {//如果poison模块存在则调用对应函数，否则直接把id写入属性
				if(defined('MOD_POISON')) \poison\poison_record_pid($isk, $npcid);
				else $isk = $npcid;
			}
		}
		return $data;
	}
	
	//道具具体数值的处理，本模块为直接返回
	//和上面那个函数mapitem_row_data_process()的定位有些重复，根据情况选择吧
	function mapitem_single_data_attr_process($iname, $ikind, $ieff, $ista, $iskind, $imap){
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
		
		//$mi = array_randompick($mipool);//从discover_extra_item()正常运行的角度考虑，必须用shuffle
		shuffle($mipool);
		$mi = $mipool[0];
		
		$itms0 = focus_item($mi);
		if($itms0){
			itemfind();
			$mipool = array_slice($mipool, 1);
			discover_extra_item($mipool);
			return true;
		} else {
			$log .= "但是什么都没有发现。<br>";
		}
		$mode = 'command';
		return false;
	}
	
	//用于发现额外物品。如果发现，需要从$mipool里把道具删除
	function discover_extra_item($mipool){
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
			if($iid > 0) $db->query("DELETE FROM {$tablepre}mapitem WHERE iid='$iid'");
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
		if(check_discover_item_found()) {
			return discover_item();
		}
		return $chprocess($schmode);
	}

	//判定是否发现道具
	//传参$debuff为发现率增益值（减益值）
	function check_discover_item_found($buff = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$find_obbs = $buff + calculate_itemfind_obbs()*calculate_itemfind_obbs_multiplier();
		$dice = rand(0,99);
		//echo $dice.' '.$find_obbs.'<br>';
		if($dice < $find_obbs) {
			return true;
		}
		return false;
	}
	
	function pre_act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		//在手里有道具的情况下阻止意料之外的指令，防止道具被洗掉
		//如果有模块在这之前执行并且获得道具那就没办法了……
		if(!empty($hp) && !empty($itms0) && strpos($action,'corpse')===false && !in_array($command, Array('itm0','dropitm0','itemget','itemmerge','enter')) && false === strpos($command, 'swap')){
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
		eval(import_module('sys','player'));
		$itemcmd = get_var_input('itemcmd');
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
				list($merge1, $merge2) = get_var_input('merge1', 'merge2');
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
				list($from, $to) = get_var_input('from', 'to');
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