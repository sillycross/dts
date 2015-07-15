<?php if(!defined('IN_GAME')) exit('Access Denied'); if($command=='check') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="pcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="pcmng">
<input type="hidden" id="command" name="command" value="list">
<input type="hidden" name="start" value="<?php } else { echo '___aakO'; } ?><?php echo $start?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="checkmode" value="<?php } else { echo '___aakP'; } ?><?php echo $checkmode?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">    
<input type="hidden" name="checkinfo" value="<?php } else { echo '___aakQ'; } ?><?php echo $checkinfo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="name" value="<?php } else { echo '___aakR'; } ?><?php echo $pc['name']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="pid" value="<?php } else { echo '___aakS'; } ?><?php echo $pc['pid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">

<input type="hidden" name="itm0" value="<?php } else { echo '___aakT'; } ?><?php echo $pc['itm0']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="itme0" value="<?php } else { echo '___aakU'; } ?><?php echo $pc['itme0']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="itmk0" value="<?php } else { echo '___aakV'; } ?><?php echo $pc['itmk0']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="itms0" value="<?php } else { echo '___aakW'; } ?><?php echo $pc['itms0']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="itmsk0" value="<?php } else { echo '___aakX'; } ?><?php echo $pc['itmsk0']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<table class="admin">
<tr>        
<th>属性名</th>
<th>属性值</th>
<th>装备属性</th>
<th>装备数值</th>
<th>包裹属性</th>
<th>包裹数值</th>
</tr>
<tr>        
<td>姓名</td>
<td><?php } else { echo '___aakY'; } ?><?php echo $pc['name']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>武器</td>
<td><input size="20" type="text" name="wep" value="<?php } else { echo '___aakZ'; } ?><?php echo $pc['wep']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
<td>包裹1</td>
<td><input size="20" type="text" name="itm1" value="<?php } else { echo '___aak0'; } ?><?php echo $pc['itm1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
</tr>
<tr>        
<td>性别</td>
<td><input size="20" type="text" name="gd" value="<?php } else { echo '___aak1'; } ?><?php echo $pc['gd']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="wepk" value="<?php } else { echo '___aak2'; } ?><?php echo $pc['wepk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="itmk1" value="<?php } else { echo '___aak3'; } ?><?php echo $pc['itmk1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>学号</td>
<td><input size="20" type="text" name="sNo" value="<?php } else { echo '___aak4'; } ?><?php echo $pc['sNo']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="wepe" value="<?php } else { echo '___aak5'; } ?><?php echo $pc['wepe']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="itme1" value="<?php } else { echo '___aak6'; } ?><?php echo $pc['itme1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>头像</td>
<td><input size="20" type="text" name="icon" value="<?php } else { echo '___aak7'; } ?><?php echo $pc['icon']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="weps" value="<?php } else { echo '___aak8'; } ?><?php echo $pc['weps']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="itms1" value="<?php } else { echo '___aak9'; } ?><?php echo $pc['itms1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>社团</td>
<td><input size="20" type="text" name="club" value="<?php } else { echo '___aak.'; } ?><?php echo $pc['club']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="wepsk" value="<?php } else { echo '___aak-'; } ?><?php echo $pc['wepsk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="itmsk1" value="<?php } else { echo '___aala'; } ?><?php echo $pc['itmsk1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>生命</td>
<td><input size="20" type="text" name="hp" value="<?php } else { echo '___aalb'; } ?><?php echo $pc['hp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>防具(体)</td>
<td><input size="20" type="text" name="arb" value="<?php } else { echo '___aalc'; } ?><?php echo $pc['arb']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
<td>包裹2</td>
<td><input size="20" type="text" name="itm2" value="<?php } else { echo '___aald'; } ?><?php echo $pc['itm2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
</tr>
<tr>
<td>最大生命</td>
<td><input size="20" type="text" name="mhp" value="<?php } else { echo '___aale'; } ?><?php echo $pc['mhp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="arbk" value="<?php } else { echo '___aalf'; } ?><?php echo $pc['arbk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="itmk2" value="<?php } else { echo '___aalg'; } ?><?php echo $pc['itmk2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>体力</td>
<td><input size="20" type="text" name="sp" value="<?php } else { echo '___aalh'; } ?><?php echo $pc['sp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="arbe" value="<?php } else { echo '___aali'; } ?><?php echo $pc['arbe']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="itme2" value="<?php } else { echo '___aalj'; } ?><?php echo $pc['itme2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>最大体力</td>
<td><input size="20" type="text" name="msp" value="<?php } else { echo '___aalk'; } ?><?php echo $pc['msp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="arbs" value="<?php } else { echo '___aall'; } ?><?php echo $pc['arbs']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="itms2" value="<?php } else { echo '___aalm'; } ?><?php echo $pc['itms2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>基础攻击</td>
<td><input size="20" type="text" name="att" value="<?php } else { echo '___aaln'; } ?><?php echo $pc['att']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="arbsk" value="<?php } else { echo '___aalo'; } ?><?php echo $pc['arbsk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="itmsk2" value="<?php } else { echo '___aalp'; } ?><?php echo $pc['itmsk2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>基础防御</td>
<td><input size="20" type="text" name="def" value="<?php } else { echo '___aalq'; } ?><?php echo $pc['def']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>防具(头)</td>
<td><input size="20" type="text" name="arh" value="<?php } else { echo '___aalr'; } ?><?php echo $pc['arh']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
<td>包裹3</td>
<td><input size="20" type="text" name="itm3" value="<?php } else { echo '___aals'; } ?><?php echo $pc['itm3']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
</tr>
<tr>
<td>位置</td>
<td><input size="20" type="text" name="pls" value="<?php } else { echo '___aalt'; } ?><?php echo $pc['pls']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="arhk" value="<?php } else { echo '___aalu'; } ?><?php echo $pc['arhk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="itmk3" value="<?php } else { echo '___aalv'; } ?><?php echo $pc['itmk3']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>等级</td>
<td><input size="20" type="text" name="lvl" value="<?php } else { echo '___aalw'; } ?><?php echo $pc['lvl']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="arhe" value="<?php } else { echo '___aalx'; } ?><?php echo $pc['arhe']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="itme3" value="<?php } else { echo '___aaly'; } ?><?php echo $pc['itme3']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>经验</td>
<td><input size="20" type="text" name="exp" value="<?php } else { echo '___aalz'; } ?><?php echo $pc['exp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="arhs" value="<?php } else { echo '___aalA'; } ?><?php echo $pc['arhs']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="itms3" value="<?php } else { echo '___aalB'; } ?><?php echo $pc['itms3']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>钱</td>
<td><input size="20" type="text" name="money" value="<?php } else { echo '___aalC'; } ?><?php echo $pc['money']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="arhsk" value="<?php } else { echo '___aalD'; } ?><?php echo $pc['arhsk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="itmsk3" value="<?php } else { echo '___aalE'; } ?><?php echo $pc['itmsk3']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>对手</td>
<td><input size="20" type="text" name="bid" value="<?php } else { echo '___aalF'; } ?><?php echo $pc['bid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>防具(腕)</td>
<td><input size="20" type="text" name="ara" value="<?php } else { echo '___aalG'; } ?><?php echo $pc['ara']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
<td>包裹4</td>
<td><input size="20" type="text" name="itm4" value="<?php } else { echo '___aalH'; } ?><?php echo $pc['itm4']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
</tr>
<tr>
<td>受伤</td>
<td><input size="20" type="text" name="inf" value="<?php } else { echo '___aalI'; } ?><?php echo $pc['inf']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="arak" value="<?php } else { echo '___aalJ'; } ?><?php echo $pc['arak']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="itmk4" value="<?php } else { echo '___aalK'; } ?><?php echo $pc['itmk4']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>怒气</td>
<td><input size="20" type="text" name="rage" value="<?php } else { echo '___aalL'; } ?><?php echo $pc['rage']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="arae" value="<?php } else { echo '___aalM'; } ?><?php echo $pc['arae']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="itme4" value="<?php } else { echo '___aalN'; } ?><?php echo $pc['itme4']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>基础姿态</td>
<td><input size="20" type="text" name="pose" value="<?php } else { echo '___aalO'; } ?><?php echo $pc['pose']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="aras" value="<?php } else { echo '___aalP'; } ?><?php echo $pc['aras']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="itms4" value="<?php } else { echo '___aalQ'; } ?><?php echo $pc['itms4']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>应战策略</td>
<td><input size="20" type="text" name="tactic" value="<?php } else { echo '___aalR'; } ?><?php echo $pc['tactic']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="arask" value="<?php } else { echo '___aalS'; } ?><?php echo $pc['arask']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="itmsk4" value="<?php } else { echo '___aalT'; } ?><?php echo $pc['itmsk4']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>杀人数</td>
<td><input size="20" type="text" name="killnum" value="<?php } else { echo '___aalU'; } ?><?php echo $pc['killnum']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>防具(足)</td>
<td><input size="20" type="text" name="arf" value="<?php } else { echo '___aalV'; } ?><?php echo $pc['arf']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
<td>包裹5</td>
<td><input size="20" type="text" name="itm5" value="<?php } else { echo '___aalW'; } ?><?php echo $pc['itm5']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
</tr>
<tr>
<td>殴熟</td>
<td><input size="20" type="text" name="wp" value="<?php } else { echo '___aalX'; } ?><?php echo $pc['wp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="arfk" value="<?php } else { echo '___aalY'; } ?><?php echo $pc['arfk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="itmk5" value="<?php } else { echo '___aalZ'; } ?><?php echo $pc['itmk5']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>斩熟</td>
<td><input size="20" type="text" name="wk" value="<?php } else { echo '___aal0'; } ?><?php echo $pc['wk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="arfe" value="<?php } else { echo '___aal1'; } ?><?php echo $pc['arfe']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="itme5" value="<?php } else { echo '___aal2'; } ?><?php echo $pc['itme5']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>枪熟</td>
<td><input size="20" type="text" name="wg" value="<?php } else { echo '___aal3'; } ?><?php echo $pc['wg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="arfs" value="<?php } else { echo '___aal4'; } ?><?php echo $pc['arfs']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="itms5" value="<?php } else { echo '___aal5'; } ?><?php echo $pc['itms5']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>投熟</td>
<td><input size="20" type="text" name="wc" value="<?php } else { echo '___aal6'; } ?><?php echo $pc['wc']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="arfsk" value="<?php } else { echo '___aal7'; } ?><?php echo $pc['arfsk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="itmsk5" value="<?php } else { echo '___aal8'; } ?><?php echo $pc['itmsk5']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>爆熟</td>
<td><input size="20" type="text" name="wd" value="<?php } else { echo '___aal9'; } ?><?php echo $pc['wd']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>饰品</td>
<td><input size="20" type="text" name="art" value="<?php } else { echo '___aal.'; } ?><?php echo $pc['art']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
<td>包裹6</td>
<td><input size="20" type="text" name="itm6" value="<?php } else { echo '___aal-'; } ?><?php echo $pc['itm6']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"></td>
</tr>
<tr>
<td>灵熟</td>
<td><input size="20" type="text" name="wf" value="<?php } else { echo '___aama'; } ?><?php echo $pc['wf']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="artk" value="<?php } else { echo '___aamb'; } ?><?php echo $pc['artk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>类型</td>
<td><input size="20" type="text" name="itmk6" value="<?php } else { echo '___aamc'; } ?><?php echo $pc['itmk6']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>队伍名称</td>
<td><input size="20" type="text" name="teamID" value="<?php } else { echo '___aamd'; } ?><?php echo $pc['teamID']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="arte" value="<?php } else { echo '___aame'; } ?><?php echo $pc['arte']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>效果</td>
<td><input size="20" type="text" name="itme6" value="<?php } else { echo '___aamf'; } ?><?php echo $pc['itme6']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td>队伍密码</td>
<td><input size="20" type="text" name="teamPass" value="<?php } else { echo '___aamg'; } ?><?php echo $pc['teamPass']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="arts" value="<?php } else { echo '___aamh'; } ?><?php echo $pc['arts']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>耐久</td>
<td><input size="20" type="text" name="itms6" value="<?php } else { echo '___aami'; } ?><?php echo $pc['itms6']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
<tr>
<td></td>
<td></td>
<td>子类型</td>
<td><input size="20" type="text" name="artsk" value="<?php } else { echo '___aamj'; } ?><?php echo $pc['artsk']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
<td>子类型</td>
<td><input size="20" type="text" name="itmsk6" value="<?php } else { echo '___aamk'; } ?><?php echo $pc['itmsk6']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="20"></td>
</tr>
</table>
<input type="submit" value="修改玩家数值" onclick="$('command').value = 'submitedit'">
</form> 
<form method="post" name="pcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="pcmng">
<input type="hidden" name="command" value="list">
<input type="submit" value="返回玩家管理">
</form>
<?php } else { echo '___aaml'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="pcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="pcmng">
<input type="hidden" id="command" name="command" value="find">
<input type="hidden" name="start" value="<?php } else { echo '___aamm'; } ?><?php echo $start?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" name="pagemode" value="">
<table class="admin">
<tr>
<th colspan=2>查找玩家</th>
</tr>
<tr>
<td>条件：
<select name="checkmode">
<option value="name" 
<?php } else { echo '___aamn'; } ?><?php if($checkmode == 'name') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeB'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>玩家名
<option value="teamID" 
<?php } else { echo '___aamo'; } ?><?php if($checkmode == 'teamID') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeB'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>队伍名称
<option value="club" 
<?php } else { echo '___aamp'; } ?><?php if($checkmode == 'club') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeB'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>社团
</select>类似
</td>
<td>
<input size="30" type="text" name="checkinfo" id="checkinfo" value="<?php } else { echo '___aamq'; } ?><?php echo $checkinfo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" maxlength="30"><input type="submit" value="查找玩家" onclick="javascript:document.pcmng.pagemode.value='ref'">
</td>
</tr>
</table>
<br>
<input type="submit" value="上一页" onclick="javascript:document.pcmng.pagemode.value='up';">
<span class="yellow"><?php } else { echo '___aamr'; } ?><?php echo $resultinfo?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<input type="submit" value="下一页" onclick="javascript:document.pcmng.pagemode.value='down';">
<table class="admin">
<tr>
<th>选</th>
<th>姓名</th>
<th>性别</th>
<th>学号</th>
<th>等级</th>
<th>位置</th>
<th>队伍</th>
<th>状态</th>
<th>体力</th>
<th>生命</th>
<th>社团</th>
<th>金钱</th>
<th>熟练</th>
<th>武器</th>
<th>操作</th>
</tr>
<?php } else { echo '___aams'; } ?><?php if(isset($pcdata)) { if(is_array($pcdata)) { foreach($pcdata as $n => $pc) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td><input type="checkbox" id="pc_<?php } else { echo '___aamt'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" name="pc_<?php } else { echo '___aamu'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" value="<?php } else { echo '___aadl'; } ?><?php echo $pc['pid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"></td>
<td><span 
<?php } else { echo '___aamv'; } ?><?php if($pc['hp']<=0 || $pc['state']>=10) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>class="red"
<?php } else { echo '___aamw'; } ?><?php } ?>
><?php echo $pc['name']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td><?php } else { echo '___aamx'; } ?><?php echo $sexinfo[$pc['gd']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['sNo']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['lvl']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $plsinfo[$pc['pls']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['teamID']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><span 
<?php } else { echo '___aamy'; } ?><?php if($pc['state']>=10) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>class="red"
<?php } else { echo '___aamw'; } ?><?php } ?>
><?php echo $stateinfo[$pc['state']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td><?php } else { echo '___aamx'; } ?><?php echo $pc['sp']?>/<?php echo $pc['msp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['hp']?>/<?php echo $pc['mhp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $clubinfo[$pc['club']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['money']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['wp']?>/<?php echo $pc['wk']?>/<?php echo $pc['wg']?>/<?php echo $pc['wc']?>/<?php echo $pc['wd']?>/<?php echo $pc['wf']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $pc['wep']?>/<?php echo $pc['wepe']?>/<?php echo $pc['weps']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="submit" value="查看/修改详细资料" onclick="$('command').value='edit_<?php } else { echo '___aamz'; } ?><?php echo $n?>_<?php echo $pc['pid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>'"></td>
</tr>
<?php } else { echo '___aamA'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td colspan="2">
<input type="checkbox" name="pc_all" onchange="for(i=0; i<=<?php } else { echo '___aamB'; } ?><?php echo $n?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>;i++){if(! $('pc_'+i).disabled){if(this.checked==true){$('pc_'+i).checked=true}else{$('pc_'+i).checked=false}}}">全选
</td>
<td colspan="13" style="text-align:center">
<input type="submit" value="复活所选玩家" onclick="$('command').value='live'">
<input type="submit" value="杀死所选玩家" onclick="$('command').value='kill'">
<input type="submit" value="清除所选玩家" onclick="$('command').value='del'">
</td>
</tr>
<?php } else { echo '___aamC'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></table>

</form>
<?php } else { echo '___aamD'; } ?><?php } ?>

