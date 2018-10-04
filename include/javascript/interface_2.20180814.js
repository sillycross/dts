////////////////////////////////////////////////////////////////////////
///////////////////////////////标签页//////////////////////////////////
////////////////////////////////////////////////////////////////////////
now_tag = 'common';

function tag_choose(tgt)
{
//	if(tgt != 'common') hotkey_ok=false;
//	else hotkey_ok=true;
	now_tag = tgt;
	tag_display_init();
}

function tag_display_init(){
	jQuery('.cmd_tag').removeClass('choosed');
	jQuery('#'+now_tag+'_cmd_tag').addClass('choosed');
	jQuery('.cmd_subpage').css('display','none');
	jQuery('#'+now_tag+'_cmd_subpage').css('display','block');
}

////////////////////////////////////////////////////////////////////////
///////////////////////////////装备和包裹省略显示切换//////////////////////////////////
////////////////////////////////////////////////////////////////////////

now_profile_mode = 'eqp';

function profile_mode_init(){
	if('eqp' == now_profile_mode) {
		jQuery('#profile_eqp,#profile_pack_short').css('display','block');
		jQuery('#profile_eqp_short,#profile_pack').css('display','none');
	}else{
		jQuery('#profile_eqp,#profile_pack_short').css('display','none');
		jQuery('#profile_eqp_short,#profile_pack').css('display','block');
	}
}

function profile_mode_switch(){
	if('eqp' == now_profile_mode) now_profile_mode = 'pack';
	else now_profile_mode = 'eqp';
	profile_mode_init();
}

////////////////////////////////////////////////////////////////////////
///////////////////////////////包裹选项//////////////////////////////////
////////////////////////////////////////////////////////////////////////

pack_switch_from = 0;
pack_switch_to = 0;

function pack_switch_set(packi){
	if(pack_switch_from && pack_switch_from != packi && !pack_switch_to) {
		pack_switch_to = packi;
	}else{
		pack_switch_from = packi;
		pack_switch_to = 0;
	}
	pack_switch_update_display();
}

function pack_switch_update_display(){
	for(var i=1;i<=6;i++){
		if(jQuery('#pack_switch_'+i).length > 0) {
			jQuery('#pack_switch_'+i).parent().removeClass('pack_from pack_to');
			if(i == pack_switch_from) jQuery('#pack_switch_'+i).parent().addClass('pack_from');
			else if(i == pack_switch_to) jQuery('#pack_switch_'+i).parent().addClass('pack_to');
		}
	}
	if(pack_switch_from && pack_switch_to) {
		jQuery('#pack_cmd_switch')[0].disabled = false;
		jQuery('#pack_cmd_merge')[0].disabled = false;
	}else{
		jQuery('#pack_cmd_switch')[0].disabled = true;
		jQuery('#pack_cmd_merge')[0].disabled = true;
	}
}

function pack_send_cmd_prepare(tp){
	if(!pack_switch_from || !pack_switch_to) return;
	var name1, name2;
	if(tp == 'merge') {
		name1 = 'merge1'; name2 = 'merge2';
	}else if(tp == 'switch'){
		name1 = 'from'; name2 = 'to';
	}
	if(jQuery('#'+name1).length > 0){
		jQuery('#'+name1)[0].value=pack_switch_from;
	}else{
		jQuery('#subcmd').after("<input type='hidden' id='"+name1+"' name='"+name1+"' value='"+pack_switch_from+"'>");
	}
	if(jQuery('#'+name2).length > 0){
		jQuery('#'+name2)[0].value=pack_switch_to;
	}else{
		jQuery('#subcmd').after("<input type='hidden' id='"+name2+"' name='"+name2+"' value='"+pack_switch_to+"'>");
	}
}

////////////////////////////////////////////////////////////////////////
///////////////////////////////log div特殊显示//////////////////////////////////
////////////////////////////////////////////////////////////////////////

log_hover_detail_height = 0;

//在原位新建一个隐藏的log div
function log_hover_detail_init(log_detail_height){
	//var tmp_log_cont = jQuery('#log_cont')[0].innerHTML;
	jQuery('#log').after('<div id="log_hover_detail" class="log_block log_hover_detail" style="display:none;">'+log_cont_raw+'</div>');
	jQuery('#log_hover_detail').css('height',log_detail_height);
	jQuery('#log').bind('mouseover',log_hover_detail_mouseover);
	jQuery('#log_hover_detail').bind('mouseout',log_hover_detail_mouseout);
}

function log_hover_detail_clean(){
	jQuery('#log').unbind('mouseover');
	jQuery('#log_hover_detail').unbind('mouseout');
	jQuery('#log_hover_detail').remove();
}

function log_hover_detail_mouseover(){
	jQuery('#log_cont').css('display','none');
	jQuery('#log_hover_detail').css('display','block');
}

function log_hover_detail_mouseout(){
	jQuery('#log_cont').css('display','block');
	jQuery('#log_hover_detail').css('display','none');
}
////////////////////////////////////////////////////////////////////////
///////////////////////////////商店选项//////////////////////////////////
////////////////////////////////////////////////////////////////////////
function shop_selected_display(sid)
{
	for(var i=1;i<=999;i++){
		if(jQuery('#shop_option_'+i).length > 0) {
			if(i == sid) {
				jQuery('#shop_option_'+i).parent().children('.corpse_info').removeClass('white').addClass('yellow b');
				jQuery('#shop_option_'+i).parent().children('.glow_buttons').removeClass('white').addClass('yellow');
			}
			else {
				jQuery('#shop_option_'+i).parent().children('.corpse_info').removeClass('yellow b').addClass('white');
				jQuery('#shop_option_'+i).parent().children('.glow_buttons').removeClass('yellow').addClass('white');
			}
		}
	}
}
////////////////////////////////////////////////////////////////////////
///////////////////////////////合成选项//////////////////////////////////
////////////////////////////////////////////////////////////////////////

function itemmix_switch(packi){
	if(jQuery('#mitm'+packi.toString()).length > 0)
		jQuery('#mitm'+packi.toString()).val('0' == jQuery('#mitm'+packi.toString()).val() ? 1 : 0);
	itemmix_update_display();
}
function itemmix_update_display(){
	for(var i=1;i<=6;i++){
		if(jQuery('#mitm'+i.toString()).length > 0){
			if('0' == jQuery('#mitm'+i.toString()).val()) jQuery('#mitmtick'+i.toString()).parent().removeClass('ticked');
			else jQuery('#mitmtick'+i.toString()).parent().addClass('ticked');
		}
	}
}