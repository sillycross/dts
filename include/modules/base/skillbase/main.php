<?php

namespace skillbase
{
	global $acquired_list;
	global $parameter_list;
	global $ppid;	//当前玩家pid
	
	function init() 
	{
		global $ppid; $ppid = -1;
	}
	
	function skillbase_set_ppid()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase','player'));
		$ppid = $pid;
	}
	
	function b64_conv_to_value($c)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ('a'<=$c && $c<='z') return ord($c)-ord('a');
		if ('A'<=$c && $c<='Z') return ord($c)-ord('A')+26;
		if ('0'<=$c && $c<='9') return ord($c)-ord('0')+52;
		if ($c=='+') return 62;
		if ($c=='-') return 63;
		eval(import_module('player'));
		throw new Exception('bad nskill value '.$nskill);
	}
	
	function value_conv_to_b64($c)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($c>=0)
		{
			if ($c<=25) return chr(ord('a')+$c);
			if ($c<=51) return chr(ord('A')+$c-26);
			if ($c<=61) return chr(ord('0')+$c-52);
			if ($c==62) return '+';
			if ($c==63) return '-';
		}
		throw new Exception('bad char in skillbase '.$c);
	}
	
	function skillbase_load(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		
		$ac_list=Array();
		if (strlen($pa['nskill'])%2!=0) throw new Exception('bad nskill value '.$pa['nskill']);
		for ($i=0; $i<strlen($pa['nskill'])/2; $i++)
		{
			$c=b64_conv_to_value($pa['nskill'][$i*2])*64+b64_conv_to_value($pa['nskill'][$i*2+1]);
			$ac_list[$c]=1;
		}
		$para=explode(',',$pa['nskillpara']);
		$cn=count($para);
		
		$para_list=Array();
		if ($cn%2!=1) throw new Exception('bad nskillpara value '.$pa['nskillpara']);
		for ($i=0; $i<($cn-1)/2; $i++)
		{
			$para_list[base64_decode($para[$i*2])]=base64_decode($para[$i*2+1]);
		}
		$pa['acquired_list']=$ac_list;
		$pa['parameter_list']=$para_list;
		if ($pa['pid']==$pid)
		{
			eval(import_module('skillbase'));
			$acquired_list = $pa['acquired_list'];
			$parameter_list = $pa['parameter_list'];
		}
		
		skill_onload_event($pa);
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skillbase_save(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		skill_onsave_event($pa);
		
		eval(import_module('player','skillbase'));
		if ($pa['pid']==$pid)
		{
			$ac_list=$acquired_list;
			$para_list=$parameter_list;
		}
		else
		{
			$ac_list=$pa['acquired_list'];
			$para_list=$pa['parameter_list'];
		}
		
		$ns='';
		foreach ($ac_list as $skillkey => $skillvalue)
		{
			if ($skillvalue == 1)
			{
				$skillkey=(int)$skillkey;
				$x=$skillkey/64; $y=$skillkey%64; $x=(int)$x;
				$ns.=value_conv_to_b64($x).value_conv_to_b64($y);
			}
		}
		
		$pl='';
		foreach ($para_list as $skillkey => $skillvalue)
		{
			$pl.=base64_encode($skillkey).','.base64_encode($skillvalue).',';
		}
		
		if ($pa['pid']==$pid)
		{
			$nskill=$ns;
			$nskillpara=$pl;
		}

		$pa['nskill']=$ns;
		$pa['nskillpara']=$pl;
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pdata);
		skillbase_load($pdata);
	}
	
	function fetch_playerdata($Pname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa=$chprocess($Pname);
		skillbase_load($pa);
		return $pa;
	}
	
	function fetch_playerdata_by_pid($pid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa=$chprocess($pid);
		skillbase_load($pa);
		return $pa;
	}
	
	function player_save($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skillbase_save($data);
		$chprocess($data);
	}
	
	function skill_acquire($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skillbase'));
		$skillid=(int)$skillid;
		if ($pa == NULL || $pa['pid']==$pid)
		{
			if ($pa == NULL) { \player\update_sdata(); $pa=$sdata; }
			$acquired_list[$skillid]=1;
		}
		else
		{
			$pa['acquired_list'][$skillid]=1;
		}
		$func='skill'.$skillid.'\\acquire'.$skillid;
		if (defined('MOD_SKILL'.$skillid)) $func($pa);
	}
	
	function skill_lost($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skillbase'));
		$skillid=(int)$skillid;
		if ($pa == NULL || $pa['pid']==$pid)
		{
			if ($pa == NULL) { \player\update_sdata(); $pa=$sdata; }
			$acquired_list[$skillid]=0;
		}
		else
		{
			$pa['acquired_list'][$skillid]=0;
		}
		$func='skill'.$skillid.'\\lost'.$skillid;
		if (defined('MOD_SKILL'.$skillid)) $func($pa);
	}
	
	function skill_query($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillid=(int)$skillid;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || $pa['pid']==$ppid) return ((isset($acquired_list[$skillid])) && ($acquired_list[$skillid]==1));
		return ((isset($pa['acquired_list'][$skillid])) && ($pa['acquired_list'][$skillid]==1));
	}
	
	function get_acquired_skill_array(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || $pa['pid']==$ppid)
		{
			$ret=Array();
			foreach($acquired_list as $key=>$value) if ($value==1) array_push($ret,$key);
			return $ret;
		}
		else
		{
			$ret=Array();
			foreach($pa['acquired_list'] as $key=>$value) if ($value==1) array_push($ret,$key);
			return $ret;
		}
	}
	
	function skill_setvalue($skillid, $skillkey, $skillvalue, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillkey=$skillid.'_'.$skillkey;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || $pa['pid']==$ppid) 
		{
			$parameter_list[$skillkey]=$skillvalue;
		}
		else
		{
			$pa['parameter_list'][$skillkey]=$skillvalue;
		}
	}
	
	function skill_getvalue($skillid, $skillkey, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillkey=$skillid.'_'.$skillkey;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || $pa['pid']==$ppid) 
		{
			if (isset($parameter_list[$skillkey])) return $parameter_list[$skillkey]; else return NULL;
		}
		else
		{
			if (isset($pa['parameter_list'][$skillkey])) return $pa['parameter_list'][$skillkey]; else return NULL;
		}
	}
	
	function skill_delvalue($skillid, $skillkey, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillkey=$skillid.'_'.$skillkey;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || $pa['pid']==$ppid) 
		{
			unset($parameter_list[$skillkey]);
		}
		else
		{
			unset($pa['parameter_list'][$skillkey]);
		}
	}
}

?>
