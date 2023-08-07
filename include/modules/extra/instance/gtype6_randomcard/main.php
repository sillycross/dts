<?php

namespace gtype6
{
	function init() {}
	
	//随机卡片模式自动选择肉鸽来客
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if (6==$gametype){
			$card=1001;
		}
		return $card;
	}
	
	//随机卡片模式自动选择肉鸽来客，禁止其他卡片
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if(6==$gametype)
		{
			foreach($card_ownlist as $cv){
				if($cv) $card_disabledlist[$cv][]='e3';
			}
		}
		return $card_disabledlist;
	}
	
	//随机卡片模式选卡界面特殊显示
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		//标准模式自动选择挑战者
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = $chprocess($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
		
		if(6==$gametype)
		{
			$cardChosen = 1001;//自动选择肉鸽来客
			$card_ownlist[] = 1001;
			$packlist[] = $cards[1001]['pack'] = 'Divine Random';
			$hideDisableButton = 0;
		}
		
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
}

?>