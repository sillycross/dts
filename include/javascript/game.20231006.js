////////////////////////////////////////////////////////////////////////
/////////////////////////////快捷键函数/////////////////////////////////
////////////////////////////////////////////////////////////////////////

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
			else if(ky==70){
				hotkey_click('f');
			}
			else if(ky==66){
				hotkey_click('b');
			}
			else if(ky==82){
				hotkey_click('r');
			}
			//数字键123456
			else if(ky >= 49 && ky <= 54){
				var kc=(ky-48).toString();
				hotkey_click(kc);
			}
		}
	}	
}

function hotkey_click(hkid){
	var hk = $(hkid);
	if (hk) hk.click();
	else if ((hkid == 'zz' || hkid == 'z' || hkid == 'x') && $('zx')) $('zx').click();
	else if (hkid.length > 1) {
		hk = $(hkid.substr(0,1));
		if (hk) hk.click();
	}
	var jobj = jQuery('#hotkey2_'+hkid.substr(0,1));
	if(jobj.length > 0) {
		jobj.attr("disabled",true);
		if(jobj.parent().hasClass('cmd_positioner')) {
			jobj.parent().addClass('grey');
		}
	}
}

////////////////////////////////////////////////////////////////////////
/////////////////////////////计时器函数/////////////////////////////////
////////////////////////////////////////////////////////////////////////

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

////////////////////////////////////////////////////////////////////////
/////////////////////////////界面杂项函数/////////////////////////////////
////////////////////////////////////////////////////////////////////////

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

function sleep(millis)
{
	var date = new Date();
	var curDate = null;
	do { curDate = new Date(); } while(curDate-date < millis);
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
/////////////////////////////AJAX相关函数/////////////////////////////////
////////////////////////////////////////////////////////////////////////

in_replay_mode = 0;
last_sender = '';

js_stop_flag = 0;

disableAllCommands = 0;

function postCmd(formName,sendto,srcdom,disableall){
	//把来源div设成灰色
	if(srcdom && 'object'==typeof(srcdom)) {
		srcdom.disabled = true;
		if(jQuery(srcdom).parent().hasClass('cmd_positioner')) {
			jQuery(srcdom).parent().addClass('grey');
		}
	}
	
	if (in_replay_mode == 1) return false;
	if (disableAllCommands == 1) return false;
	//jQuery('#hoverHintMsg').css({'display':'none'});//清除悬停提示 效果不好，改到show的时候清除
	if(disableall) jQuery('.cmdbutton').attr("disabled","disabled");//屏蔽所有按钮
	hotkey_ok = false;//屏蔽快捷键
	
	if(jQuery('#loading').length > 0) jQuery('#loading').css({'display':'block'});//显示Loading画面
	
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
					$('connect-status-text').innerHTML='<span class="grey b">正在连接..</span>';
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
		if ($('connect-status-text')) $('connect-status-text').innerHTML='<span class="grey b">连接已建立</span>';
	}	
	if ($('oprecorder'))
	{
		$('oprecorder').value=""; last_sender='';
	}
	return false;
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
log_cont_raw = '';

//处理AJAX返回值的主函数
function showData(sdata){
	if (js_stop_flag) return;
	jQuery('#hoverHintMsg').css({'display':'none'});//清除悬浮提示
	if(jQuery('#loading').length > 0) jQuery('#loading').css({'display':'none'});//隐藏Loading画面
	hotkey_ok = true;//重启快捷键
	log_cont_raw = '';//重置log原始记录
	templateid = 1;
	if(jQuery('#templateid').length > 0) templateid = jQuery('#templateid')[0].value;
	if(typeof sdata == 'string' && sdata.indexOf('<html>') > 0 && sdata.indexOf('</html>') > 0){
		document.write(sdata);
		return;
	}
	
	//消除上次操作的气泡框
	bubblebox_clear_all();
	
	//回放模式中不需要解压
	if (typeof in_replay_mode == 'undefined' || in_replay_mode == 0){
		try {
			sdata= decodeURIComponent( escape( JXG.decompress(sdata) ) );
		} catch (e) {
			$("error").innerHTML = sdata;
			return;
		}
	}
	
	//开始处理
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
					if('log'==id && cont !== '') {
						logshow = 1;
						log_cont_raw = cont;
					}
				}else{
					$(id).innerHTML = '';
				}
			}
		}
		//log div处理
		if(1==logshow) {
			jQuery('#log').css({'display':log_display});
			//界面2给log div加滚动条，修改log div大小
			if(1 != templateid && 'block' == log_display) {
				//清除悬浮事件
				if('undefined' != typeof(log_hover_detail_clean)) log_hover_detail_clean();
				if('undefined' != typeof(shwData['display'])  && 'undefined' != typeof(shwData['display']['log_height']) && 0 != shwData['display']['log_height']) {
					jQuery('#log').css('height', shwData['display']['log_height']);
				}else{
					jQuery('#log').css('height','');
				}
				var tmp_log = jQuery('#log')[0].innerHTML;
				jQuery('#log').empty();
				jQuery('#log').append("<div id='log_cont'><div>"+tmp_log+"</div></div>");
				//jQuery('#log').append("<img style='display:none;' type='hidden' src='img/blank.png' onload=\"jQuery(function() { jQuery('#log_cont').jScrollPane(); });\">");
				jQuery(function() { jQuery('#log_cont').jScrollPane(); });
			}
		}else {
			jQuery('#log').css({'display':'none'});
		}
		//自动生成快捷键图标
		jQuery('.cmd_positioner').children(':button').each(function(){
			if (-1 != jQuery.inArray(jQuery(this).attr('id'), ['z','zz','a','aa','s','ss','d','dd','q','qq','w','ww','e','ee','x','c','v','zx'])){
				if('zx' == jQuery(this).attr('id')){
					jQuery(this).before("<div class='hotkey_mark'>X</div>");
					var tmp_right0 = jQuery(this).parent().children('.hotkey_mark').css('right').replace('px','');
					var tmp_width0 = jQuery(this).parent().children('.hotkey_mark').css('width').replace('px','');
					var tmp_right = (Number(tmp_right0) +  Number(tmp_width0) + 5).toString()+'px';
					jQuery(this).before("<div class='hotkey_mark' style='right:"+tmp_right+"'>Z</div>");
				}else{
					jQuery(this).before("<div class='hotkey_mark'>"+jQuery(this).attr('id').substr(0,1).toUpperCase()+"</div>");
				}
			}
		});		
		//图片链接更新
		var sDs = shwData['src'];
		for(var id in sDs){
			if($(id)!=null){
				$(id).src = sDs[id];
			}
		}
		//游戏内计时器
		if (shwData['timing'])
		{
			var sDt = shwData['timing'];
			for(var tid in sDt){
				if(sDt[tid]['on']==true && in_replay_mode==0){//非录像模式才计时
					if('undefined'!=typeof(timingforbidden[tid])) {//如果被禁用，那么开启
						delete(timingforbidden[tid]);
					}
					var t = sDt[tid]['timing'];
					var tm = sDt[tid]['mode'];
					intv = fmt = null;
					if('undefined'!=typeof(sDt[tid]['interval'])) var intv = sDt[tid]['interval'];
					if('undefined'!=typeof(sDt[tid]['format'])) var fmt = sDt[tid]['format'];
					if('undefined'==typeof(timinglist) || 'undefined'==typeof(timinglist[tid])) {
						updateTime(tid,t,tm,intv,fmt);
					}else{
						var restart_flag = 0;
						if(!timinglist[tid]['timing'] && !timinglist[tid]['mode']) restart_flag = 1;
						timinglist[tid]['timing'] = t;
						timinglist[tid]['mode'] = tm;
						if(restart_flag) updateTime(tid,t,tm,intv,fmt);
					}
				}else{//on==false时关闭计时器
					timingforbidden[tid] = 1;
				}
			}
		}
		//冷却时间计时器，回头应该统一
		if(shwData['timer'] && typeof(timerid)=='undefined'){
			demiSecTimerStarter(datalib_decode(shwData['timer']));
		}
		//房间踢人计时器
		if ($('roomkick_timer') && typeof(RoomKickTimerId)=='undefined')
		{
			RoomKickTimerId=setInterval("room_kick_timer()",1000);
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
		//各种特效处理
		showData_effect(shwData);
	}
	//重载悬浮提示
	floating_hint();
	
	//新界面自动切到对应的标签页和状态界面
	if(jQuery('.cmd_tag').length > 0 && 'common' != now_tag) tag_display_init();
	if(jQuery('#profile_eqp').length > 0) profile_mode_init();
}

function showData_effect(shwData) {
	//NPC对白依次显示效果
	bel=jQuery('.dialogue-bubble')
	if(bel.length > 0)
	{
		var tmp_display = jQuery('.dialogue-bubble:first').css('display');
		bel.css('display','none');
		
		effect_npcchat_display_offset = 0;
		effect_npcchat_display_timer(2000, tmp_display);
	}
	//图标闪烁效果
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
			  		efel.css({'z-index':'5','position':'relative'});
					}
			  }
			}	else if (ef == 'chatref'){
				chat('ref',15000);
			}
		}
	}
	//自动强化特效
	if ($('autopower_totnum') && typeof(AutopowerTimerId)=='undefined')
	{
		AutopowerLogTimer();
		totnum = parseInt($('autopower_totnum').innerHTML);
		if (totnum>1) 
			AutopowerTimerId=setInterval("AutopowerLogTimer()",parseInt($('autopower_cd').innerHTML));
	}
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

//清除所有特效
function effect_clear_all(){
	jQuery("*").removeClass('Pulse');
	jQuery("*").removeClass('TransPulse');
}

effect_npcchat_display_offset = 0;

//NPC对话气泡每隔1秒显示
function effect_npcchat_display_timer(intv, tp){
	var intv = intv || 1000;
	var tp = tp || 'block';
	bel = jQuery('.dialogue-bubble');
	bel.each(function(i){
		if (i == effect_npcchat_display_offset){
			jQuery(this).css('display',tp);
			jQuery(this).prev().addClass('fade');
		}
	});
	effect_npcchat_display_offset ++ ;
	if(effect_npcchat_display_offset < bel.length)
		setTimeout("effect_npcchat_display_timer(" + intv + ",'" + tp + "')",intv);
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

function bubblebox_hide(bid)
{
	if ($('fmsgbox'+(bid.toString())))
	{
		$('fmsgbox-container').appendChild($('fmsgbox'+(bid.toString())));
		$('fmsgbox'+(bid.toString())).style.display = 'none';
	}
}

function bubblebox_switch(bid){
	if ($('fmsgbox'+(bid.toString())))
	{
		if($('fmsgbox'+(bid.toString())).style.display == 'none') bubblebox_show(bid);
		else bubblebox_hide(bid);
	}
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

////////////////////////////////////////////////////////////////////////
///////////////////////////////开局剧情//////////////////////////////////
////////////////////////////////////////////////////////////////////////
shooting_current = 0;

function shooting_showdefault()
{
	var sdft;
	sdft = 'op_sht_'+(shooting_current).toString();
	if(jQuery('#'+sdft).length > 0) {
		jQuery('#'+sdft).css('display','block');
	}
	shooting_checkbuttons();
}

function shooting_hideall()
{
	var sel;
	sel = jQuery('.shootings');
	sel.each(function(){
		jQuery(this).css('display','none');
	});
}

function shooting_checkbuttons()
{
	var schk;
	schk = shooting_current;
	if(jQuery('#op_sht_'+(schk-1).toString()).length <= 0) {
		jQuery('#shooting_previous').css('display','none');
	}else{
		jQuery('#shooting_previous').css('display','inline-block');
	}
	
	if(jQuery('#op_sht_'+(schk+1).toString()).length <= 0) {
		jQuery('#shooting_next').css('display','none');
		jQuery('#shooting_over').css('display','inline-block');
	}else{
		jQuery('#shooting_next').css('display','inline-block');
		jQuery('#shooting_over').css('display','none');
	}
}

function shooting_jump(num)
{
	var sjmp;
	num = parseInt(num);
	sjmp = 'op_sht_'+(shooting_current+num).toString();
	if(jQuery('#'+sjmp).length > 0) {
		shooting_hideall();
		jQuery('#'+sjmp).css('display','block');
		shooting_current += num;
	}
	shooting_checkbuttons();
}

////////////////////////////////////////////////////////////////////////
///////////////////////////////技能表//////////////////////////////////
////////////////////////////////////////////////////////////////////////
function skilldesc_onmouseover(caller_id, skill_id, srcdom)
{
	var obj = jQuery('#skl_util_'+caller_id+'_skilllearn_'+skill_id);
	obj.css('display','block');
	obj.css('top',Number(jQuery(srcdom).offset().top-jQuery(window).scrollTop()+10).toString()+'px');
	var tmp_left = Number(jQuery(srcdom).offset().left-jQuery(window).scrollLeft()+40);
	if(tmp_left+420>jQuery(window).width()) tmp_left-=460;
	obj.css('left',tmp_left.toString()+'px');
}

function skilldesc_onmouseout(caller_id, skill_id)
{
	jQuery('#skl_util_'+caller_id+'_skilllearn_'+skill_id).css('display','none');
}