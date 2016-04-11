function cardMover(){
	var path="gamedata/cache/card.json"; 
	ind=$('cardsel').value;
	jQuery.getJSON(path, function(data){ 
		$('cname').innerHTML=data[ind].name;
		$('crare').innerHTML=data[ind].rare;
		cc=data[ind].cost;
		if (cc>0){
			$('ccost').innerHTML="消耗 "+cc;
		}else{
			$('ccost').innerHTML="获取 "+Math.abs(cc);
		}
		$('cdesc').innerHTML=data[ind].desc;
		$('ceffect').innerHTML=data[ind].effect;
		//$('cpack').innerHTML=data[ind].pack;
	}); 
}