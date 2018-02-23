var ms;
hotkey_ok = true;
refchat_ok = true;

function hotkey(evt) 
{ 
	if(hotkey_ok && document.activeElement.tagName != 'INPUT'){
		evt = (evt) ? evt : ((window.event) ? window.event : '');
		var ky = evt.keyCode ? evt.keyCode : evt.which;
		flag=1;//是否完成冷却
		if (ms!=undefined) {
			if (ms>0) flag=0;
		}	
		//双字母id=冷却时间内不可执行的操作 单字母可以执行
		if(!evt.ctrlKey && !evt.altKey && !evt.shiftKey){
			if(ky==90){
				flag==1 ? hotkey_click('zz') : hotkey_click('z');
			}
			else if(ky==65){
				flag==1 ? hotkey_click('aa') : hotkey_click('a');
			}
			else if(ky==83){
				flag==1 ? hotkey_click('ss') : hotkey_click('s');
			}
			else if(ky==68){
				flag==1 ? hotkey_click('dd') : hotkey_click('d');
			}
			else if(ky==81){
				flag==1 ? hotkey_click('qq') : hotkey_click('q');
			}
			else if(ky==87){
				flag==1 ? hotkey_click('ww') : hotkey_click('w');
			}
			else if(ky==69){
				flag==1 ? hotkey_click('ee') : hotkey_click('e');
			}
			else if(ky==88){
				hotkey_click('x');
			}
			else if(ky==67){
				hotkey_click('c');
			}
			else if(ky==86){
				hotkey_click('v');
			}
			else if(ky >= 49 && ky <= 54){
				var kc=(ky-48).toString();
				hotkey_click(kc);
			}
		}
	}	
}

function hotkey_click(hkid){
	hk = $(hkid);
	if (hk) hk.click();
	else if (hkid.length > 1) {
		hk = $(hkid.substr(0,1));
		if (hk) hk.click();
	}
}

function demiSecTimer(){
	if($('timer') && ms>=itv)	{
		ms -= itv;
		var sec = Math.floor(ms/1000);
		var dsec = Math.floor((ms%1000)/100);
		$('timer').innerHTML = sec + '.' + dsec;
	}	else {
		ms=0;
		clearInterval(timerid);
		delete timerid; 
	}
}

function demiSecTimerStarter(msec){
	itv = 100;//by millisecend
	ms = msec;
	if (typeof timerid == 'undefined') timerid = setInterval(demiSecTimer,itv);
}

//已废弃
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

function userIconMover(){
	var ugd = $('male').checked ? 'm' : 'f';
	var uinum = $('icon').selectedIndex;
	$('userIconImg').innerHTML = '<img src="img/' + ugd + '_' + uinum + '.gif" alt="' + uinum + '">';
}
function dniconMover(){
	var npc = $('npc') ? true : false;
	var dngd = npc ? 'n' : ($('male').checked ? 'm' : 'f');
	var dninum = $('dnicon').options[$('dnicon').selectedIndex].value;
	$('dniconImg').innerHTML = '<img src="img/' + dngd + '_' + dninum + '.gif" alt="' + dninum + '">';
}

function showNotice(sNotice) {
	if ($('notice')) $('notice').innerText = sNotice;
}

function sl(id) {
	$(id).checked = true;
	replay_record_DOM_path($(id));
}

in_replay_mode = 0;
last_sender = '';

js_stop_flag = 0;

disableAllCommands = 0;

function postCmd(formName,sendto,disableall){
	if (in_replay_mode == 1) return;
	if (disableAllCommands == 1) return;
	jQuery('#hoverHintMsg').css({display:"none"});//清除悬停提示
	if(disableall) jQuery('.cmdbutton').attr("disabled","disabled");//屏蔽所有按钮
	hotkey_ok = false;//屏蔽快捷键
	if(jQuery('#loading')) jQuery('#loading').css({display:"block"});//显示Loading画面
	
	replay_listener();	//IE Hack，处理IE不支持catch的问题
	var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBody(document.forms[formName]);
	oXmlHttp.open("post", sendto, true);
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (sendto=='roomupdate.php')
			{
				if ($('connect-status-text')) 
					$('connect-status-text').innerHTML='<span class="grey">正在连接..</span>';
			}
			if (oXmlHttp.status == 200) {
				if (oXmlHttp.responseText!='')
				{
					showData(oXmlHttp.responseText);
				}
			} else {
				showNotice(oXmlHttp.statusText);
			}
			if (sendto=='roomupdate.php' && !js_stop_flag)
			{
				//这是一个长轮询……
				room_get_update();
			}
		}
	}
	oXmlHttp.send(sBody);
	if (sendto=='roomupdate.php')
	{
		if ($('connect-status-text')) $('connect-status-text').innerHTML='<span class="grey">连接已建立</span>';
	}	
	if ($('oprecorder'))
	{
		$('oprecorder').value=""; last_sender='';
	}
}

function sleep(millis)
{
	var date = new Date();
	var curDate = null;
	do { curDate = new Date(); } while(curDate-date < millis);
}

//adv3开启时才有效的html缓存解码函数
function datalib_decode(val)
{
	if (typeof ___datalib == 'undefined') return val;
	val = val.toString();
	var ret = ''; var i=0;
	while (i<val.length)
	{
		if (i<val.length-2 && val[i]=='_' && val[i+1]=='_' && val[i+2]=='_')
		{
			ret = ret + ___datalib[val.substr(i+3,4)];
			i+=7;
		}
		else
		{
			ret = ret + val[i];
			i=i+1;
		}
	}
	return ret;
}

room_cur_chat_maxcid = 0;

function showData(sdata){
	if (js_stop_flag) return;
	if(jQuery('#loading')) jQuery('#loading').css({display:"none"});//隐藏Loading画面
	hotkey_ok = true;//重启快捷键
	if(typeof sdata == 'string' && sdata.indexOf('<html>') > 0 && sdata.indexOf('</html>') > 0){
		document.write(sdata);
		return;
	}
	
	////////////////////////////////////////////////////////////////////////
	///////////////////////////////气泡框相关/////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	//消除上次操作的气泡框
	bubblebox_clear_all();
	
	////////////////////////////////////////////////////////////////////////
	////////////////////////////////标准操作/////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	//回放模式中不需要解压
	if (typeof in_replay_mode == 'undefined' || in_replay_mode == 0){
		try {
			sdata= decodeURIComponent( escape( JXG.decompress(sdata) ) );
		} catch (e) {
			$("error").innerHTML = sdata;
			return;
		}
	}
	
	if (typeof no_json_decode == 'undefined' || no_json_decode == 0)
		shwData = JSON.parse(sdata);
	else  shwData = sdata;
	
	if(!shwData) return;
	
	//url属性存在时直接跳转
	if(shwData['url']) {
		if (in_replay_mode==0)
		{
			js_stop_flag = 1;
			if (datalib_decode(shwData['url']) == 'error.php')	//gexit error
			{
				var form = jQuery('<form action="error.php" name="errorpost" method="post" style="display:none;"><input type="text" name="errormsg" value="' + datalib_decode(shwData['errormsg']) + '" /></form>');
				jQuery('body').append(form); form.submit();
			}
			else  
			{
				window.location.href = datalib_decode(shwData['url']);
			}
		}
	}else{
		//遍历value属性，对每一个子属性（键值对）都寻找匹配的DOM并改写其value
		var sDv = shwData['value'];
		for(var id in sDv){
			if($(id)!=null){
				$(id).value = datalib_decode(sDv[id]);
			}
		}
		//遍历innerHTML属性，对每一个子属性（键值对）都寻找匹配的DOM并改写其innerHTML
		var sDi = shwData['innerHTML'];
		var logshow = 0;
		for(var id in sDi){
			if($(id)!=null){
				if(sDi['id'] !== ''){
					var cont = datalib_decode(sDi[id]);
					$(id).innerHTML = cont;
					if('log'==id && cont !== '') logshow = 1;
				}else{
					$(id).innerHTML = '';
				}
			}
		}
		if(1==logshow) jQuery('#log').css({display:"table-cell"});
		else jQuery('#log').css({display:"none"});
		var sDs = shwData['src'];
		for(var id in sDs){
			if($(id)!=null){
				$(id).src = sDs[id];
			}
		}
		if (shwData['timing'])
		{
			var sDt = shwData['timing'];
			for(var tid in sDt){
				if(sDt[tid]['on']==true){
					var t = sDt[tid]['timing'];
					var tm = sDt[tid]['mode'];
					intv = fmt = null;
					if('undefined'!=typeof(sDt[tid]['interval'])) var intv = sDt[tid]['interval'];
					if('undefined'!=typeof(sDt[tid]['format'])) var fmt = sDt[tid]['format'];
					if('undefined'==typeof(timinglist) || 'undefined'==typeof(timinglist[tid])) {
						updateTime(tid,t,tm,intv,fmt);
					}else{
						timinglist[tid]['timing'] = t;
						timinglist[tid]['mode'] = tm;
					}
				}
			}
		}
		//这个回头应该做到专门的js里去
		if (shwData['effect'])
		{
			effect_clear_all();
			var sDe = shwData['effect'];
			for(var ef in sDe){
				if(ef == 'pulse'){
					for (var ei=0; ei<sDe[ef].length; ei++){
						if(sDe[ef][ei].search('__BUTTON__') >= 0){
							sDe[ef][ei] = sDe[ef][ei].replace('__BUTTON__','');
							var efel=jQuery(sDe[ef][ei]).parent(".itmsingle").children(".cmdbutton");
							//alert(efel.length);
						}else{
							var efel=jQuery(sDe[ef][ei]);
						}
						if(efel.length > 0){
							if(efel.is('img') || efel.is('select')) efel.addClass("TransPulse");
				  		else efel.addClass("Pulse");
						}
				  }
				}	else if (ef == 'chatref'){
					chat('ref',15000);
				}
			}
		}
		//聊天刷新
		if (shwData['lastchat'])
		{
			var sDc = shwData['lastchat'];
			for(var id in sDc)
			{
				if (sDc[id]['cid']>room_cur_chat_maxcid)
				{
					roomchat_changed_flag = 0;
					room_cur_chat_maxcid = sDc[id]['cid'];
					if ($('chatdata-text')) 
					{
						$('chatdata-text').innerHTML+=sDc[id]['data'];
						roomchat_changed_flag = 1;
					}
					if (roomchat_changed_flag) roomchat_refresh();
				}
			}
		}
	}
	if(shwData['timer'] && typeof(timerid)=='undefined'){
		demiSecTimerStarter(datalib_decode(shwData['timer']));
	}
	
	////////////////////////////////////////////////////////////////////////
	//////////////////////////////自动强化特效////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	if ($('autopower_totnum') && typeof(AutopowerTimerId)=='undefined')
	{
		AutopowerLogTimer();
		totnum = parseInt($('autopower_totnum').innerHTML);
		if (totnum>1) 
			AutopowerTimerId=setInterval("AutopowerLogTimer()",parseInt($('autopower_cd').innerHTML));
	}
	
	////////////////////////////////////////////////////////////////////////
	////////////////////////////////房间踢人/////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	if ($('roomkick_timer') && typeof(RoomKickTimerId)=='undefined')
	{
		RoomKickTimerId=setInterval("room_kick_timer()",1000);
	}
	
	//重载悬浮提示
	floating_hint();
}

var refchat = null;

function chat(mode,reftime) {
	clearTimeout(refchat);
	var oXmlHttp = zXmlHttp.createRequest();
	var sBody = getRequestBody(document.forms['sendchat']);
	if(mode == 'news') oXmlHttp.open("post", "news.php", true);
	else oXmlHttp.open("post", "chat.php", true);
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
	if(refchat_ok) {
		if(mode == 'news') refchat = setTimeout("chat('news',rtime)",rtime);
		else refchat = setTimeout("chat('ref',rtime)",rtime);
	}
}

function showChatdata(chatdata) {
	try {
		chatdata= decodeURIComponent( escape( JXG.decompress(chatdata) ) );
	} catch (e) {
		$("error").innerHTML = chatdata;
		return;
	}
	chatdata = JSON.parse(chatdata);
	if(null==chatdata) return;
	var lastvarid='';
	var pdomid='';
	var cdata='';
	if(chatdata['cmd']) {
		if('chat-ref-stop' == chatdata['cmd']) {
			refchat_ok = false;
		}
	}
	if(chatdata['msg']) {
		lastvarid='lastcid';
		pdomid='chatlist';
		cdata=chatdata['msg'];
	}else if(chatdata['news']){
		lastvarid='lastnid';
		pdomid='newslist';
		cdata=chatdata['news'];
	}
	if(lastvarid) {
		$(lastvarid).value=chatdata[lastvarid];
		newchat = '';
		for(var cid in cdata) {
			if(cid == 'toJSONString') {continue;}
			if($(cid) && $(cid).parentNode.id==pdomid) {//遇到相同id的聊天记录就先清掉，防止多刷
				$(pdomid).removeChild($(cid));
			}
			newchat += cdata[cid];
		}
		if(newchat) $(pdomid).innerHTML=newchat+$(pdomid).innerHTML;
	}
}

//oSourceObj按钮本身
//oTargetObj要开闭的标签
//shutAble开启后是否能关闭
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

////////////////////////////////////////////////////////////////////////
/////////////////////////////录像记录相关/////////////////////////////////
////////////////////////////////////////////////////////////////////////

function replay_record_DOM_path(sender)
{
	//这个函数sender参数必须确实是个DOM Element
	if (sender.tagName!='INPUT' && sender.tagName!='SELECT' && sender.tagName!='OPTION') return;
	if (sender != last_sender)
	{
		last_sender = sender;

		var ret=new String(''); var x=sender;
		while (x!=document && x.id!='game_interface')
		{
			var c=0;
			while (x!=x.parentNode.firstElementChild)
			{
				c++; x=x.previousElementSibling;
			}
			ret=Number(c).toString()+'.'+ret;
			x=x.parentNode;
		}
		if (x.id!='game_interface') return;
		ret=ret+',';
		if (sender.tagName=='OPTION') ret=ret+'e,';	//OPTION选完后加一个暂停
		if ($('oprecorder')) $('oprecorder').value+=ret;
	}
}

function replay_listener(e)
{
	if (in_replay_mode == 1) return;
	var sender = (e && e.target) || (window.event && window.event.srcElement);
	var ev = (e || window.event);
	if (typeof ev == 'undefined') return;
	if (!ev) return;
	if (ev.type!='click') return;
	if (typeof sender == 'undefined') return;
	if (!sender) return;
	replay_record_DOM_path(sender);
}

//监听按钮原理：
//因为坑爹的postCmd没把event传参进去，直接来肯定不行了，出现次数太多也没法改
//然后各个浏览器大概是这样：
//IE: 不支持catch，但支持window.event
//firefox: 支持catch，但不支持window.event
//chrome: 都支持
//所以先定义一个catch的event listener，这样非IE的浏览器都能保证listener在postCmd前执行了
//然后postCmd里如果发现有window.event，就主动调用一下listener，如果id和上次listener的id不相同就记录下来，这样就支持IE了
//chrome中虽然listener会被调用两次，但这两个id是相同的，不会重复记录
//这样似乎惟一的问题是select的onchange event因为某些神秘原因会覆盖掉onclick event... 考虑到select+onchange用的不多，手动处理吧

document.addEventListener('click',replay_listener,true);

////////////////////////////////////////////////////////////////////////
///////////////////////////称号技能鼠标悬浮特效////////////////////////////
////////////////////////////////////////////////////////////////////////

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

////////////////////////////////////////////////////////////////////////
//////////////////////////////自动强化特效////////////////////////////////
////////////////////////////////////////////////////////////////////////

function AutopowerLogTimer()
{
	if (!$('autopower_curnum'))
	{
		clearInterval(AutopowerTimerId);
		delete AutopowerTimerId;
		return;
	}
	curnum = parseInt($('autopower_curnum').innerHTML);
	totnum = parseInt($('autopower_totnum').innerHTML);
	if (curnum>1 && curnum<=totnum)
		$('autopower'+Number(curnum-1).toString()).style.display = 'none';
	
	$('autopower'+Number(curnum).toString()).style.display = 'inline';
	$('autopower_curnum').innerHTML=Number(curnum+1).toString();
	
	if (curnum == totnum)
	{	
		clearInterval(AutopowerTimerId);
		delete AutopowerTimerId;
	}
}

//特效相关
function effect_clear_all(){
	jQuery("*").removeClass('Pulse');
	jQuery("*").removeClass('TransPulse');
}

////////////////////////////////////////////////////////////////////////
///////////////////////////////气泡框相关/////////////////////////////////
////////////////////////////////////////////////////////////////////////

function bubblebox_hide_all()
{
	while ($('fmsgbox-container').firstChild!=null) 
	{
		$('fmsgbox-container').firstChild.style.display = 'none';
		$('hidden-fmsgbox-container').appendChild($('fmsgbox-container').firstChild);
	}
	while ($('hidden-persistent-fmsgbox-container').firstChild!=null) 
	{
		$('hidden-persistent-fmsgbox-container').firstChild.style.display = 'none';
		$('hidden-fmsgbox-container').appendChild($('hidden-persistent-fmsgbox-container').firstChild);
	}
}

function bubblebox_clear_all()
{
	while ($('fmsgbox-container').firstChild!=null) 
	{
		if ($('fmsgbox-container').firstChild.getAttribute('id').substr(0,17)=='fmsgboxpersistent')
			$('hidden-persistent-fmsgbox-container').appendChild($('fmsgbox-container').firstChild);
		else  $('fmsgbox-container').removeChild($('fmsgbox-container').firstChild);
	}
	while ($('hidden-fmsgbox-container').firstChild!=null) 
	{
		if ($('hidden-fmsgbox-container').firstChild.getAttribute('id').substr(0,17)=='fmsgboxpersistent')
			$('hidden-persistent-fmsgbox-container').appendChild($('hidden-fmsgbox-container').firstChild);
		else  $('hidden-fmsgbox-container').removeChild($('hidden-fmsgbox-container').firstChild);
	}
}

function bubblebox_show(bid)
{
	bubblebox_hide_all();
	if ($('fmsgbox'+(bid.toString())))
	{
		$('fmsgbox-container').appendChild($('fmsgbox'+(bid.toString())));
		$('fmsgbox'+(bid.toString())).style.display = 'block';
		jQuery(function() { jQuery('.scroll-pane'+(bid.toString())).jScrollPane(); });
	}
}



////////////////////////////////////////////////////////////////////////
/////////////////////////////发光按钮相关/////////////////////////////////
////////////////////////////////////////////////////////////////////////

function glowbutton_highlight(id)
{
	$('glowbutton-'+id+'-background').style.backgroundColor=$('glowbutton-'+id+'-color-container').innerHTML;
}

function glowbutton_dehighlight(id)
{
	$('glowbutton-'+id+'-background').style.backgroundColor='none';
}

////////////////////////////////////////////////////////////////////////
///////////////////////////////房间相关//////////////////////////////////
////////////////////////////////////////////////////////////////////////

function room_get_update()
{
	postCmd('roomcmd','roomupdate.php');
}

function roomchat_refresh()
{
	jQuery(function() 
	{ 
		var api = jQuery('.scroll-pane-chat').data('jsp');
		api.destroy();
	});
	jQuery(function() 
	{ 
		jQuery('.scroll-pane-chat').jScrollPane(); 
	});
	jQuery(function() 
	{ 
		var api = jQuery('.scroll-pane-chat').data('jsp');
		api.scrollToPercentY(100);
	});
}

function room_enter(t)
{
	window.location.href='roomcmd.php?command=enterroom&para1='+t;
}

function room_quit(t)
{
	window.location.href='roomcmd.php?command=leave';
}

function room_kick_timer()
{
	if (!$('roomkick_timer'))
	{
		clearInterval(RoomKickTimerId);
		delete RoomKickTimerId;
		return;
	}
	curnum = parseInt($('roomkick_timer').innerHTML);
	curnum --;
	$('roomkick_timer').innerHTML = Number(curnum).toString();
	if (curnum<=0)
	{
		if ($('command')) $('command').value=''; 
		postCmd('roomcmd','roomcmd.php');	//发送踢人命令
	}
}

function show_fixed_div(t)
{
	if ($(t))
	{
		$(t).style.display='block';
	}
}

function hide_fixed_div(t)
{
	if ($(t))
	{
		$(t).style.display='none';
	}
}

////////////////////////////////////////////////////////////////////////
////////////////////////////buff图标相关/////////////////////////////////
////////////////////////////////////////////////////////////////////////

function BuffIconSecTimer()
{
	var x=jQuery(".bufficon_style_1");
	for (var i=0; i<x.length; i++)
	{
		var a=x[i];
		var t=parseInt(a.firstElementChild.innerHTML);
		var nt=parseInt(a.firstElementChild.nextElementSibling.innerHTML);
		var od=parseInt(a.firstElementChild.nextElementSibling.nextElementSibling.innerHTML);
		nt++;
		if (nt>=t)
		{
			nt=t;
			if (od==1)
			{
				a.style.display="none";
				continue;
			}
		}
		a.firstElementChild.nextElementSibling.innerHTML=nt;
		var wh=Math.round(nt/t*32);
		var z=a.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling;
		z.nextElementSibling.firstElementChild.firstElementChild.nextElementSibling.firstElementChild.nextElementSibling.innerHTML=Number(t-nt).toString();;
		z.style.top=(Number(wh).toString())+'px';
		z.firstElementChild.style.top=(Number(-wh).toString())+'px';
		delete a; delete t; delete nt; delete od; delete wh; delete z;
	}
	var x=jQuery(".bufficon_style_2");
	for (var i=0; i<x.length; i++)
	{
		var a=x[i];
		var t=parseInt(a.firstElementChild.innerHTML);
		var nt=parseInt(a.firstElementChild.nextElementSibling.innerHTML);
		if (nt<=t)
		{
			nt++;
			if (nt>t)
			{
				a.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild.style.display='block';
				a.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.style.display='none';
			}
		}
		a.firstElementChild.nextElementSibling.innerHTML=nt;
		if (nt>t) nt=t;
		var wh=Math.round(nt/t*32);
		var z=a.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.nextElementSibling;
		console.debug(z);
		z.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.nextElementSibling.firstElementChild.nextElementSibling.innerHTML=Number(t-nt).toString();;
		z.style.height=(Number(wh).toString())+'px';
		delete a; delete t; delete nt; delete od; delete wh; delete z;
	}
	delete x; 
}

setInterval("BuffIconSecTimer()",1000);

////////////////////////////////////////////////////////////////////////
////////////////////////////铁拳无敌蓄力技能///////////////////////////////
////////////////////////////////////////////////////////////////////////

xuli_flag = 0;
xuli_tick = 0;
xuli_pret = 1;
xuli_maxt = 1;

function xuli_available()
{
	return $('progressbar-inner3') && $('progressbar-text3')
}

function xuli_setpercentage(p)
{
	$('progressbar-inner3').style.width=p+'%';
	$('progressbar-text3').innerHTML=p+'%';
}

function xuli_tickfunc()
{
	xuli_tick++;
	if (xuli_available() && xuli_tick>xuli_pret) 
	{
		x=(xuli_tick-xuli_pret)/xuli_maxt;
		if (x>1) x=1;
		x=Math.round(x*100);
		xuli_setpercentage(x);
	}
}
