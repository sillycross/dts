<?php

namespace bmp_util
{
	function init() {}
	
	function bmp_binwriteint(&$str, $pos, $val)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pos=(int)$pos; $val=(int)$val;
		$str[$pos]=pack('C',$val%256); $pos++;
		$val=(int)floor($val/256);
		$str[$pos]=pack('C',$val%256); $pos++;
		$val=(int)floor($val/256);
		$str[$pos]=pack('C',$val%256); $pos++;
		$val=(int)floor($val/256);
		$str[$pos]=pack('C',$val%256); $pos++;
	}
	
	function gen_bmp(&$content, $width, $height) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$bmp_header = Array(
			0x42, 0x4d, 0, 0, 0, 0, 0, 0, 
			0, 0, 54, 0, 0, 0, 40, 0, 
			0, 0, 0, 0, 0, 0, 0, 0, 
			0, 0, 1, 0, 24, 0, 0, 0, 
			0, 0, 0xc4, 0x0e, 0, 0, 0xc4, 0x0e,
			0, 0, 0, 0, 0, 0, 0, 0,
			0, 0, 0, 0, 0, 0,
		);
		
		$str='';
		for ($i=0; $i<=53; $i++) $str.=chr($bmp_header[$i]);
		
		bmp_binwriteint($str,2,$width*$height*3+54);
		bmp_binwriteint($str,18,$width);
		bmp_binwriteint($str,22,$height);
		bmp_binwriteint($str,34,$width*$height*3+2);
		
		for ($i=0; $i<$width*$height; $i++)
		{
			$str.=pack('C',$content[$i][2]); 
			$str.=pack('C',$content[$i][1]); 
			$str.=pack('C',$content[$i][0]); 
		}
		$str.=pack('C',0);
		$str.=pack('C',0);
		return $str;
	}
}

?>
