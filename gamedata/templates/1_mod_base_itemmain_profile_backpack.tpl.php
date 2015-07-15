<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<TABLE border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
<tr>
<TD class=b1 width="60"><span>道具用途</span></TD>
<TD class=b1><span>名称</span></TD>
<TD class=b1 width="70"><span>属性</span></TD>
<TD class=b1 width="40"><span>效</span></TD>
<TD class=b1 width="40"><span>耐</span></TD>
</TR>
<tr>          			  
<TD class=b2 height="26"><span>
<?php if($tpldata['itmk1_words']) { ?>
<?php echo $tpldata['itmk1_words']?>
<?php } else { ?>
<span class="grey">包裹1</span>
<?php } ?>
</span></TD>
<TD class=b3><span>
<?php if($itms1) { ?>
<?php echo $itm1?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span></TD>
<TD class=b3><span><?php echo $tpldata['itmsk1_words']?></span></TD>
<TD class=b3><span><?php echo $itme1?></span></TD>
<TD class=b3><span><?php echo $itms1?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php if($tpldata['itmk2_words']) { ?>
<?php echo $tpldata['itmk2_words']?>
<?php } else { ?>
<span class="grey">包裹2</span>
<?php } ?>
</span></TD>
<TD class=b3><span>
<?php if($itms2) { ?>
<?php echo $itm2?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span></TD>
<TD class=b3><span><?php echo $tpldata['itmsk2_words']?></span></TD>
<TD class=b3><span><?php echo $itme2?></span></TD>
<TD class=b3><span><?php echo $itms2?></span></TD>
</tr>
<tr>          		
<TD class=b2 height="26"><span>
<?php if($tpldata['itmk3_words']) { ?>
<?php echo $tpldata['itmk3_words']?>
<?php } else { ?>
<span class="grey">包裹3</span>
<?php } ?>
</span></TD>
<TD class=b3><span>
<?php if($itms3) { ?>
<?php echo $itm3?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span></TD>
<TD class=b3><span><?php echo $tpldata['itmsk3_words']?></span></TD>
<TD class=b3><span><?php echo $itme3?></span></TD>
<TD class=b3><span><?php echo $itms3?></span></TD>
</tr>
<tr>          	
<TD class=b2 height="26"><span>
<?php if($tpldata['itmk4_words']) { ?>
<?php echo $tpldata['itmk4_words']?>
<?php } else { ?>
<span class="grey">包裹4</span>
<?php } ?>
</span></TD>
<TD class=b3><span>
<?php if($itms4) { ?>
<?php echo $itm4?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span></TD>
<TD class=b3><span><?php echo $tpldata['itmsk4_words']?></span></TD>
<TD class=b3><span><?php echo $itme4?></span></TD>
<TD class=b3><span><?php echo $itms4?></span></TD>
</tr>
<tr>          			  
<TD class=b2 height="26"><span>
<?php if($tpldata['itmk5_words']) { ?>
<?php echo $tpldata['itmk5_words']?>
<?php } else { ?>
<span class="grey">包裹5</span>
<?php } ?>
</span></TD>
<TD class=b3><span>
<?php if($itms5) { ?>
<?php echo $itm5?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span></TD>
<TD class=b3><span><?php echo $tpldata['itmsk5_words']?></span></TD>
<TD class=b3><span><?php echo $itme5?></span></TD>
<TD class=b3><span><?php echo $itms5?></span></TD>
</tr>
<tr>          			  
<TD class=b2 height="26"><span>
<?php if($tpldata['itmk6_words']) { ?>
<?php echo $tpldata['itmk6_words']?>
<?php } else { ?>
<span class="grey">包裹6</span>
<?php } ?>
</span></TD>
<TD class=b3><span>
<?php if($itms6) { ?>
<?php echo $itm6?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span></TD>
<TD class=b3><span><?php echo $tpldata['itmsk6_words']?></span></TD>
<TD class=b3><span><?php echo $itme6?></span></TD>
<TD class=b3><span><?php echo $itms6?></span></TD>
</tr>
</table>