var ms;
function hotkey(evt) 
{ 
	if(document.activeElement.tagName != 'INPUT'){
		evt = (evt) ? evt : ((window.event) ? window.event : '');
		var ky = evt.keyCode ? evt.keyCode : evt.which;
		flag=1;
		if (ms!=undefined) {
			if (ms>0) flag=0;
		}	
		//双字母id=冷却时间内不可执行的操作 单字母可以执行
		if(!evt.ctrlKey && !evt.altKey && !evt.shiftKey){
			if (flag==1){
				if(ky==90){
					$('zz').click();
				}
				if(ky==65){
					$('aa').click();
				}
				if(ky==68){
					$('dd').click();
				}	
				if(ky==69){
					$('ee').click();
				}
				if(ky==83){
					$('ss').click();
				}
				if(ky==81){
					$('qq').click();
				}	
				if(ky==87){
					$('ww').click();
				}	
			}
			if(ky==90){
				$('z').click();
			}
			if(ky==88){
				$('x').click();
			}
			if((ky>=49)&&(ky<=54)){
				var kc=(ky-48).toString();
				$(kc).click();
			}
			if(ky==65){
				$('a').click();
			}
			if(ky==67){
				$('c').click();
			}
			if(ky==68){
				$('d').click();
			}	
			if(ky==69){
				$('e').click();
			}
			if(ky==83){
				$('s').click();
			}
			if(ky==81){
				$('q').click();
			}
			if(ky==86){
				$('v').click();
			}	
			if(ky==87){
				$('w').click();
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
		ms=0;
		clearInterval(timerid);
		delete timerid; 
	}
}

function demiSecTimerStarter(msec){
	itv = 100;//by millisecend
	ms = msec;
	timerid = setInterval(demiSecTimer,itv);
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
	if ($('notice')) $('notice').innerText = sNotice;
}

function sl(id) {
	$(id).checked = true;
	replay_record_DOM_path($(id));
}

in_replay_mode = 0;
last_sender = '';

js_stop_flag = 0;

function postCmd(formName,sendto){
	if (in_replay_mode == 1) return;
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
	
	////////////////////////////////////////////////////////////////////////
	///////////////////////////////气泡框相关/////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	//消除上次操作的气泡框
	bubblebox_clear_all();
	
	////////////////////////////////////////////////////////////////////////
	////////////////////////////////标准操作/////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	//回放模式中不需要解压
	if (typeof in_replay_mode == 'undefined' || in_replay_mode == 0)
		sdata= decodeURIComponent( escape( JXG.decompress(sdata) ) );
	
	if (typeof no_json_decode == 'undefined' || no_json_decode == 0)
		shwData = JSON.parse(sdata);
	else  shwData = sdata;
	
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
	}else if(!shwData['innerHTML']) {
		$('error').innerHTML=sdata;
			//window.location.href = 'index.php';
	}else{
		sDv = shwData['value'];
		for(var id in sDv){
			if($(id)!=null){
				$(id).value = datalib_decode(sDv[id]);
			}
		}
		
		sDi = shwData['innerHTML'];
		for(var id in sDi){
			if($(id)!=null){
				if(sDi['id'] !== ''){
					$(id).innerHTML = datalib_decode(sDi[id]);
				}else{
					$(id).innerHTML = '';
				}
			}
		}
		
		if (shwData['lastchat'])
		{
			sDc = shwData['lastchat'];
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
/////////////////////////////录像回放相关/////////////////////////////////
////////////////////////////////////////////////////////////////////////

function replay_show_time_leadzero(t)
{
	if (t.length==1) return '0'+t; else return t;
}

function replay_cursor_get_time(t)
{
	var hr=Math.floor(t/3600);
	var mi=Math.floor((t-hr*3600)/60);
	var sc=Math.floor((t-hr*3600-mi*60));
	if (hr==0) 
		return Number(mi).toString()+':'+replay_show_time_leadzero(Number(sc).toString());
	else  return Number(hr).toString()+':'+replay_show_time_leadzero(Number(mi).toString())+':'+replay_show_time_leadzero(Number(sc).toString());
}

function replay_get_frame(t)
{
	left=0; right=replay_data.length;
	if (t<=replay_header['replay_optime'][left]) return left;
	if (t>=replay_header['replay_optime'][right]) return right;
	while (left!=right)
	{
		mid=Math.floor((left+right+1)/2);
		if (replay_header['replay_optime'][mid]<=t)
			left=mid;
		else  right=mid-1;
	}
	return left;
}

replay_cursor_drag_flag = 0;
replay_cursor_now_mouseon = 0;
replay_now = 0;
replay_nowframe = 0;
replay_clickrec = 0;
replay_force_refresh = 0;

function replay_set_time(t)
{
	replay_now=t;
	$('replay_now_player_time').innerHTML=replay_cursor_get_time(replay_now);
	var replay_nframe = replay_get_frame(replay_now);
	if (replay_nframe != replay_nowframe || replay_force_refresh == 1)
	{
		replay_nowframe = replay_nframe;
		showData(replay_data[replay_nowframe]);
		replay_clickrec = 0;
	}
}

function replay_cursor_set_position(mvd)
{
	$('replay_cursor').style.left=mvd+'px';
	replay_set_time(mvd/1000*replay_header['replay_timelen']);
}

function replay_bar_mouse_move_handler(e)
{
	var pos = Math.floor(e.clientX-jQuery('#replay_bar_core').offset().left);
	if (pos<0) pos=0;
	if (pos>1000) pos=1000;
	var t=pos/1000*replay_header['replay_timelen'];
	$('replay_hint').style.left=Number(Math.floor(e.clientX)+10).toString()+'px';
	$('replay_hint').style.top=Number(Math.floor(e.clientY)+10).toString()+'px';
	$('replay_hint_inner').innerHTML=replay_cursor_get_time(t);
}

function replay_cursor_drag_handler(e)
{
	if (replay_cursor_drag_flag==0) return;
	var pixel_x = e.clientX;
	var mvd = replay_cursor_start_pos + pixel_x - replay_cursor_start_mouse_x;
	mvd=Math.floor(mvd);
	if (mvd<0) mvd=0;
	if (mvd>1000) mvd=1000;
	replay_cursor_set_position(mvd);
	replay_bar_mouse_move_handler(e);
}

function replay_bar_show_time_hint()
{
	$('replay_hint').style.display="block";
}

function replay_bar_hide_time_hint()
{
	$('replay_hint').style.display="none";
}

function replay_cursor_mouse_down_handler(e)
{
	replay_cursor_start_pos=$('replay_cursor').style.left;
	replay_cursor_start_pos=parseInt(replay_cursor_start_pos.substr(0,replay_cursor_start_pos.length-2));
	replay_cursor_start_mouse_x=e.clientX;
	replay_cursor_drag_flag = 1; 
	document.addEventListener('mousemove',replay_cursor_drag_handler);
	document.addEventListener('mouseup',replay_cursor_mouse_up_handler);
	replay_bar_show_time_hint();
	e.stopPropagation();
}

function replay_cursor_mouse_up_handler(e)
{
	replay_cursor_drag_flag = 0; 
	document.removeEventListener('mousemove',replay_cursor_drag_handler);
	document.removeEventListener('mouseup',replay_cursor_drag_handler);
	if (replay_cursor_now_mouseon==0)
		$('replay_cursor').style.backgroundImage='url(img/replay_cursor.png)';
	replay_bar_hide_time_hint();
}

function replay_cursor_mouse_over_handler(e)
{
	replay_cursor_now_mouseon = 1; 
	$('replay_cursor').style.backgroundImage='url(img/replay_cursor_hover.png)';
	e.stopPropagation();
}

function replay_cursor_mouse_out_handler(e)
{
	replay_cursor_now_mouseon = 0; 
	if (replay_cursor_drag_flag==0) 
		$('replay_cursor').style.backgroundImage='url(img/replay_cursor.png)';
	e.stopPropagation();
}

function replay_bar_mouse_down_handler(e)
{
	var pos = Math.floor(e.clientX-jQuery('#replay_bar_core').offset().left);
	if (pos<0) pos=0;
	if (pos>1000) pos=1000;
	replay_cursor_set_position(pos);
	replay_cursor_mouse_down_handler(e);
}

replay_speed = 1;

function replay_switch_speed(t)
{
	if (typeof t == 'undefined')
	{
		if (replay_speed == 1) t=1; else t=0;
	}
	
	if (t==1)
	{
		replay_speed = 3;
		$('replay_speed_selector').style.backgroundColor="#bbbb00";
		$('replay_speed_selector').style.color="#000000";
	}
	else
	{
		replay_speed = 1;
		$('replay_speed_selector').style.backgroundColor="";
		$('replay_speed_selector').style.color="#ffffff";
	}
}

function replay_start_handler()
{
	if (replay_stat == 1) return;
	replay_stat = 1;
	$('replay_start_selector').style.backgroundColor="#00bb00";
	$('replay_start_selector').style.color="#000000";
	$('replay_pause_selector').style.backgroundColor="";
	$('replay_pause_selector').style.color="#ffffff";
}

function replay_pause_handler()
{
	if (replay_stat == 0) return;
	replay_stat = 0;
	$('replay_pause_selector').style.backgroundColor="#bb0000";
	$('replay_pause_selector').style.color="#000000";
	$('replay_start_selector').style.backgroundColor="";
	$('replay_start_selector').style.color="#ffffff";
}

replay_timestep = 20;

replay_player_id = -1;

function replay_clear_playerbutton_background(t)
{
	$('replay_playerbutton_'+Number(t).toString()+'_background').style.backgroundColor='transparent';
}

function replay_set_playerbutton_background(t)
{
	$('replay_playerbutton_'+Number(t).toString()+'_background').style.backgroundColor='#'+replay_data_full[t]['color'];
}

function replay_load_player(t)
{
	//似乎js的array和object都是默认就是地址引用的…… 直接赋值就可以达到引用的效果了……
	if (replay_player_id != -1 ) replay_clear_playerbutton_background(replay_player_id);
	replay_player_id =t; replay_set_playerbutton_background(replay_player_id);
	$('replay_bar_coreu2').src="gamedata/replays/"+replay_data_full[t]['repfileid']+".rep.bmp";
	$('replay_bar_coreu1').src="gamedata/replays/"+replay_data_full[t]['repfileid']+".rep.bmp";
	$('replay_bar_core').src="gamedata/replays/"+replay_data_full[t]['repfileid']+".rep.bmp";
	$('replay_bar_cored1').src="gamedata/replays/"+replay_data_full[t]['repfileid']+".rep.bmp";
	$('replay_bar_cored2').src="gamedata/replays/"+replay_data_full[t]['repfileid']+".rep.bmp";
	replay_data=replay_data_full[t]['replay_data'];
	replay_header=replay_data_full[t]['replay_header'];
	replay_oprecord=replay_data_full[t]['replay_oprecord'];
	if (typeof in_replay_mode=='undefined' || in_replay_mode==0) return;
	//更新页面
	replay_force_refresh = 1;
	replay_set_time(replay_now);
	replay_force_refresh = 0;
}

function replay_switch_player(t)
{
	if (replay_cursor_drag_flag) return;
	replay_load_player(t);
}

function replayload_progressbar(p)
{
	$('progressbar-inner').style.width=p+'%';
	$('progressbar-text').innerHTML=p+'%';
}

function replayload_progressbar2(p)
{
	$('progressbar-inner2').style.width=p+'%';
	$('progressbar-text2').innerHTML=p+'%';
}

function replay_init()
{
	//保存刚刚载入的玩家信息
	replay_data_full[replay_player_now_num]['replay_header']=replay_header;
	replay_data_full[replay_player_now_num]['replay_data']=replay_data;
	replay_data_full[replay_player_now_num]['replay_oprecord']=replay_oprecord;
	delete replay_header;
	delete replay_data;
	replay_player_now_num++;
	if (replay_player_now_num<replay_player_num_tot)
	{
		replayload_progressbar(0);
		replayload_progressbar2(Math.round(replay_player_now_num/replay_player_num_tot*100));
		$('replay_loader_now_player_num').innerHTML=replay_player_now_num+1;
		$('replay_loader_now_player').innerHTML=replay_data_full[replay_player_now_num]['repname'];
		$('replay_loader_now_repsz').innerHTML=replay_data_full[replay_player_now_num]['repsz'];
		$('replay_loader_now_opcnt').innerHTML=replay_data_full[replay_player_now_num]['repopcnt'];
		jQuery.cachedScript("gamedata/replays/"+replay_data_full[replay_player_now_num]['repfileid']+".replay.header.js");
		return;
	}
	replay_load_player(0);
	in_replay_mode = 1;
	no_json_decode = 1;
	$('replay_tot_player_time').innerHTML=replay_cursor_get_time(replay_header['replay_timelen']);
	$('replay_now_player_time').innerHTML=replay_cursor_get_time(0);
	replay_stat = 1;
	replay_pause_handler();
	setInterval('replay_show()',replay_timestep);
	/*
	replay_set_time(replay_header['replay_optime'][0]);
	replay_cursor_set_position_by_time(replay_header['replay_optime'][0]);
	*/
	showData(replay_data[0]);
}

function replay_cursor_set_position_by_time(t)
{
	t=Math.round(t/(replay_header['replay_timelen']/1000));
	if (t<0) t=0;
	if (t>1000) t=1000;
	$('replay_cursor').style.left=t+'px';
}

function replay_stimulate_click(t)
{
	if (t.length==0) return;
	if (t.length==1 && t[0]=='e') return;
	var z = $('game_interface');
	for (var i=0; i<t.length; i++)
	{
		z = z.firstElementChild;
		if (!z) return;
		for (var j=0; j<t[i]; j++)
		{
			z=z.nextElementSibling;
			if (!z) return;
		}
	}
	if (z)
	{
		if (z.tagName=='SELECT')
		{
			var xx=Number(jQuery(z).offset().left-jQuery(window).scrollLeft()).toString()+'px';
			var yy=Number(jQuery(z).offset().top-jQuery(window).scrollTop()).toString()+'px';
			z.style.position='fixed';
			z.style.zIndex=9000;
			z.style.left=xx;
			z.style.top=yy;
			var t = z.length; if (t>10) t=10;
			z.size=t;
		}
		else  if (z.tagName=='OPTION') 
		{
			if (z.parentNode.tagName=='SELECT') 
			{
				z.parentNode.style.position='';
				z.style.left='';
				z.style.top='';
				z.parentNode.style.zIndex=0;
				z.parentNode.size=1;
			}
			z.selected=1;
		}
		else  z.click();
	}
}

function replay_stimulate_click_events(t)
{
	var f = replay_get_frame(t);
	if (f+1==replay_data.length) return;
	var z = replay_oprecord[f+1].length;
	if (z==0) return;
	//最后一次点击操作在下一次操作显示前175毫秒触发
	//此前的点击操作均匀分布，但间隔不超过300毫秒
	var ts = replay_header['replay_optime'][f+1]-replay_header['replay_optime'][f];
	var pf = 0;
	if (ts<=0.175)
	{
		var ga = ts/2/z;
	}
	else 
	{
		var ga = (ts-0.175)/z;
		if (ga>0.35) 
		{
			ga=0.35; 
			pf = ts-0.175-ga*z;
		}
	}
	
	var nz = Math.floor((t-pf-replay_header['replay_optime'][f])/ga);
	while (replay_clickrec<nz && replay_clickrec<z)
	{
		replay_clickrec++;
		replay_stimulate_click(replay_oprecord[f+1][replay_clickrec-1]);
	}
}

function replay_show()
{
	if (replay_stat!=1) return;
	if (replay_cursor_drag_flag==1) return;
	replay_now += (replay_timestep/1000)*replay_speed;
	if (replay_now>replay_header['replay_timelen']+2) 
	{
		replay_now = 0;
		replay_set_time(replay_now);
		replay_cursor_set_position_by_time(replay_now);
		replay_pause_handler();
		bubblebox_show('persistent-replay-endhint');
	}
	else
	{
		replay_stimulate_click_events(replay_now);
		replay_set_time(replay_now);
		replay_cursor_set_position_by_time(replay_now);
	}
}

replay_more_player_submenu_mouse_on = 0;
replay_more_player_mouse_on = 0;

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