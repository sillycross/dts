function cardMover(){
	var path="gamedata/cache/card.json"; 
	ind=$('cardsel').value;
	jQuery.getJSON(path, function(data){ 
		$('cname').innerHTML=data[ind].name;
		$('crare').innerHTML=data[ind].rare;
		$('cdesc').innerHTML=data[ind].desc;
		$('ceffect').innerHTML=data[ind].effect;
		$('cpack').innerHTML=data[ind].pack;
	}); 
}