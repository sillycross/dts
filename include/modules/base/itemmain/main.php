<?php

namespace itemmain
{
	function init() 
	{
		eval(import_module('player'));
		global $item_equip_list;
		$equip_list=array_merge($equip_list,$item_equip_list);
	}
	
	function parse_itmk_words($k_value, $simple = 0)
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
	
	function count_itmsk_num($sk_value)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=0;
		for ($i=0; $i<strlen($sk_value); $i++)
		{
			if ('a'<=$sk_value[$i] && $sk_value[$i]<='z') $ret+=2;
			if ('A'<=$sk_value[$i] && $sk_value[$i]<='Z') $ret+=2;
			if ($sk_value[$i]=='^') $ret+=1;
		}
		$ret/=2; $ret=(int)$ret;
		return $ret;
	}
	
	function get_itmsk_array($sk_value)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		$i = 0;
		while ($i < strlen($sk_value))
		{
			$sub = substr($sk_value,$i,1); $i++;
			if(!empty($sub)){
				if ($sub=='^')
				{
					while ($i<strlen($sk_value) && '0'<=$sk_value[$i] && $sk_value[$i]<='9') 
					{
						$sub.=$sk_value[$i];
						$i++;
					}
					if ($i<strlen($sk_value) && $sk_value[$i]=='^')
					{
						$sub.='^'; $i++;
					}
					else  continue;
				}
				array_push($ret,$sub);
			}					
		}
		return $ret;		
	}
	
	function parse_itmsk_words($sk_value, $simple = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('itemmain'));
		if($sk_value && is_numeric($sk_value) === false){
			$ret = '';
			$i = 0;
			while ($i < strlen($sk_value))
			{
				$sub = substr($sk_value,$i,1); $i++;
				if(!empty($sub)){
					if ($sub=='^')
					{
						while ($i<strlen($sk_value) && '0'<=$sk_value[$i] && $sk_value[$i]<='9') 
						{
							$sub.=$sk_value[$i];
							$i++;
						}
						if ($i<strlen($sk_value) && $sk_value[$i]=='^')
						{
							$sub.='^'; $i++;
						}
						else  continue;
					}
					
					if(!empty($ret)){
						if ($simple)
							$ret .= $itemspkinfo[$sub];
						else  $ret .= '+'.$itemspkinfo[$sub];
					}else{
						$ret = $itemspkinfo[$sub];
					}					
				}
				
			}
		} else {
			if ($simple)
				$ret='';
			else  $ret =$nospk;
		}
		return $ret;
	}
		
	function parse_item_words($edata, $simple = 0)	//$simple=1时没有有属性间加号，无属性直接返回空
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','itemmain'));
		$r=Array();
		foreach ($equip_list as $k_value) {
			$z=strlen($k_value)-1;
			while ('0'<=$k_value[$z] && $k_value[$z]<='9') $z--;
			$k_value=substr($k_value,0,$z+1).'k'.substr($k_value,$z+1);
			$r[$k_value.'_words'] = parse_itmk_words($edata[$k_value],$simple);
		}

		foreach ($equip_list as $sk_value) {
			$z=strlen($sk_value)-1;
			while ('0'<=$sk_value[$z] && $sk_value[$z]<='9') $z--;
			$sk_value=substr($sk_value,0,$z+1).'sk'.substr($sk_value,$z+1);
			$r[$sk_value.'_words'] = parse_itmsk_words($edata[$sk_value],$simple);
		}
		
		return $r;
		
	}
	
	function init_profile()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		\player\update_sdata();
		$tpldata+=parse_item_words($sdata);
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
			$file = __DIR__.'/config/mapitem.config.php';
			$itemlist = openfile($file);
			$in = sizeof($itemlist);
			$an = $areanum ? ceil($areanum/$areaadd) : 0;
			for($i = 1; $i < $in; $i++) {
				if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false){
					list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
					if(($iarea == $an)||($iarea == 99)) {
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
		$itm0=$mi['itm'];
		$itmk0=$mi['itmk'];
		$itme0=$mi['itme'];
		$itms0=$mi['itms'];
		$itmsk0=$mi['itmsk'];
		$iid=$mi['iid'];
		$db->query("DELETE FROM {$tablepre}mapitem WHERE iid='$iid'");
			if($itms0){
			itemfind();
			return;
		} else {
			$log .= "但是什么都没有发现。<br>";
		}
		$mode = 'command';
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
			if ($itemcmd=='itemmove') include template(MOD_ITEMMAIN_ITEMMOVE);
			if ($itemcmd=='itemdrop') include template(MOD_ITEMMAIN_ITEMDROP);
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
				else{itemmerge($merge1,$merge2);}
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
				itemadd();
			} 
		}
		$chprocess();
	}
}

?>
