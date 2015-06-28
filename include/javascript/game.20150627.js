function hotkey(evt) 
{ 
	if(document.activeElement.tagName != 'INPUT'){
		evt = (evt) ? evt : ((window.event) ? window.event : '');
		var ky = evt.keyCode ? evt.keyCode : evt.which;
		if(!evt.ctrlKey && !evt.altKey && !evt.shiftKey){
			if(ky==90){
				$('submit').click();
			}
		}
	}	
}

//update time
function updateTime(timing,mode)
{
	if(timing){
		t = timing;
		tm = mode;
		h = Math.floor(t/3600);
		m = Math.floor((t%3600)/60);
		s = t%60;
		// add a zero in front of numbers<10
		h=checkTime(h);
		m=checkTime(m);
		s=checkTime(s);
		$('timing').innerHTML = h + ':' + m + ':' +s;
		tm ? t++ : t--;
		setTimeout("updateTime(t,tm)",1000);
	}
	else{
		window.location.reload(); 
	}
}


function demiSecTimer(){
	if($('timer') && ms>=itv)	{
		ms -= itv;
		var sec = Math.floor(ms/1000);
		var dsec = Math.floor((ms%1000)/100);
		$('timer').innerHTML = sec + '.' + dsec;
	}	else {
		clearInterval(timerid);
		delete timerid;
	}
}

function demiSecTimerStarter(msec){
	itv = 100;//by millisecend
	ms = msec;
	timerid = setInterval("demiSecTimer()",itv);
}

function itemmixchooser(){
	for(i=1;i<=6;i++){
		var mname = 'mitm'+i;
		if($(mname) != null){
			if($(mname).checked){
				$(mname).value=i;
			}
		}
	}
}

//icon select
//function iconMover(){
//	gd = document.valid.gender[0].checked ? 'm' : 'f';
//	inum = document.valid.icon.selectedIndex;
//	$('iconImg').innerHTML = '<img src="img/' + gd + '_' + inum + '.gif" alt="' + inum + '">';
//}
function userIconMover(){
	ugd = $('male').checked ? 'm' : 'f';
	uinum = $('icon').selectedIndex;
	$('userIconImg').innerHTML = '<img src="img/' + ugd + '_' + uinum + '.gif" alt="' + uinum + '">';
}
function dniconMover(){
	dngd = $('male').checked ? 'm' : 'f';
	dninum = $('dnicon').selectedIndex;
	$('dniconImg').innerHTML = '<img src="img/' + dngd + '_' + dninum + '.gif" alt="' + dninum + '">';
}

function showNotice(sNotice) {
	$('notice').innerText = sNotice;
}

function sl(id) {
	$(id).checked = true;
}

function postCmd(formName,sendto){
	var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBody(document.forms[formName]);
	oXmlHttp.open("post", sendto, true);
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				showData(oXmlHttp.responseText);
			} else {
				showNotice(oXmlHttp.statusText);
			}
		}
	}
	oXmlHttp.send(sBody);
}

function showData(sdata){
	shwData = JSON.parse(sdata);
	if(shwData['url']) {
		window.location.href = shwData['url'];
	}else if(!shwData['innerHTML']) {
		$('error').innerHTML=sdata;
			//window.location.href = 'index.php';
	}else{
		sDv = shwData['value'];
		for(var id in sDv){
			if($(id)!=null){
				$(id).value = sDv[id];
			}
		}
		sDi = shwData['innerHTML'];
		
		for(var id in sDi){
			if($(id)!=null){
				if(sDi['id'] !== ''){
					$(id).innerHTML = sDi[id];
				}else{
					$(id).innerHTML = '';
				}
			}
		}
	}
	if(shwData['timer'] && typeof(timerid)=='undefined'){
		demiSecTimerStarter(shwData['timer']);
	}
}

var refchat = null;

function chat(mode,reftime) {
	clearTimeout(refchat);
	var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBody(document.forms['sendchat']);
	oXmlHttp.open("post", "chat.php", true);
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				showChatdata(oXmlHttp.responseText);
			} else {
				showNotice(oXmlHttp.statusText);
			}
		}
	};
	oXmlHttp.send(sBody);
	if(mode == 'send'){$('chatmsg').value = '';$('sendmode').value = 'ref';}
	rtime = reftime;
	refchat = setTimeout("chat('ref',rtime)",rtime);
}


function showChatdata(jsonchat) {
	chatdata = JSON.parse(jsonchat);
	if(chatdata['msg']) {
		$('lastcid').value=chatdata['lastcid'];
		newchat = '';
		for(var cid in chatdata['msg']) {
			if(cid == 'toJSONString') {continue;}
			newchat += chatdata['msg'][cid];
		}
		$('chatlist').innerHTML = newchat + $('chatlist').innerHTML;
	}			
}

function openShutManager(oSourceObj,oTargetObj,shutAble,oOpenTip,oShutTip){
	var sourceObj = typeof oSourceObj == "string" ? document.getElementById(oSourceObj) : oSourceObj;
	var targetObj = typeof oTargetObj == "string" ? document.getElementById(oTargetObj) : oTargetObj;
	var openTip = oOpenTip || "";
	var shutTip = oShutTip || "";
	if(targetObj.style.display!="none"){
	   if(shutAble) return;
	   targetObj.style.display="none";
	   if(openTip  &&  shutTip){
	    sourceObj.innerHTML = shutTip; 
	   }
	} else {
	   targetObj.style.display="block";
	   if(openTip  &&  shutTip){
	    sourceObj.innerHTML = openTip; 
	   }
	}
}

function skill_unacquired_mouseover(e)
{
	var children = this.childNodes;
	for (var i = 0; i < children.length; i++) 
	{
		var child = children[i];
		if (child.className == 'skill_unacquired') 
		{
			child.className = 'skill_unacquired_transparent';
		}
		if (child.className == 'skill_unacquired_hint') 
		{
			child.className = 'skill_unacquired_hint_transparent';
		}
	}
}

function skill_unacquired_mouseout(e)
{
	var children = this.childNodes;
	for (var i = 0; i < children.length; i++) 
	{
		var child = children[i];
		if (child.className == 'skill_unacquired_transparent') 
		{
			child.className = 'skill_unacquired'; 
		}
		if (child.className == 'skill_unacquired_hint_transparent') 
		{
			child.className = 'skill_unacquired_hint';
		}
	}
}
