<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<TABLE border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
<TR>
<TD class=b1 width="60"><span>装备种类</span></TD>
<TD class=b1><span>名称</span></TD>
<TD class=b1 width="70"><span>属性</span></TD>
<TD class=b1 width="40"><span>效</span></TD>
<TD class=b1 width="40"><span>耐</span></TD>
</tr>
<?php if(defined('MOD_WEAPON')) { include template('MOD_WEAPON_PROFILE_WEP'); } if(defined('MOD_ARMOR')) { include template('MOD_ARMOR_PROFILE_ARMOR'); } if(defined('MOD_ARMOR_ART')) { include template('MOD_ARMOR_ART_PROFILE_ARMOR_ART'); } ?>
</table>