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

//获得当前进度条对应的全局时刻
function replay_cursor_get_global_sec(t)
{
	var tobj = new Date();
	tobj.setTime((t + replay_game_starttime)*1000);
	var tstr = tobj.Format('hh时mm分ss秒');
	return tstr;
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
	$('replay_now_global_sec').innerHTML=replay_cursor_get_global_sec(replay_now);
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

function replay_bk_handler()
{
	replay_pause_handler();
	if(replay_now<=0) return;
	var rn = replay_now;
	var i = 0;
	do {
		rn -= 0.1;
		i++;
		if(i>108000 || rn<0) break;
		rnframe = replay_get_frame(rn);
	}while(rnframe == replay_nowframe);
	if(rn<0) rn=0;
	replay_set_time(rn);
	replay_cursor_set_position_by_time(rn);
}

function replay_fd_handler()
{
	replay_pause_handler();
	rmax = replay_header['replay_timelen'];
	if(replay_now>=rmax) return;
	var rn = replay_now;
	var i = 0;
	do {
		rn += 0.1;
		i++;
		if(i>108000 || rn>rmax) break;
		rnframe = replay_get_frame(rn);		
	}while(rnframe == replay_nowframe);
	if(rn>rmax) rn=rmax;
	replay_set_time(rn);
	replay_cursor_set_position_by_time(rn);
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
	replay_fd_handler();	
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
	if (f+1>=replay_data.length) return;
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

