//getElementById
function $(id) {
	return document.getElementById(id);
}

//时间检查
function checkTime(i)
{
	if (i<10 && i>=0) {i="0" + i;}
  return i;
}

//ajax
var zXml={useActiveX:(typeof ActiveXObject!="undefined"),useXmlHttp:(typeof XMLHttpRequest!="undefined")};
zXml.ARR_XMLHTTP_VERS=["MSXML2.XmlHttp.6.0","MSXML2.XmlHttp.3.0","MSXML2.XmlHttp","Microsoft.XmlHttp"];
function zXmlHttp(){}
zXmlHttp.createRequest=function(){
	if(zXml.useXmlHttp){return new XMLHttpRequest();}
	else if(zXml.useActiveX){
		if(!zXml.XMLHTTP_VER){
			for(var i=0;i<zXml.ARR_XMLHTTP_VERS.length;i++){
				try{new ActiveXObject(zXml.ARR_XMLHTTP_VERS[i]);
					zXml.XMLHTTP_VER=zXml.ARR_XMLHTTP_VERS[i];break;}catch(oError){;}
				}
			}
		if(zXml.XMLHTTP_VER){return new ActiveXObject(zXml.XMLHTTP_VER);}
		else{throw new Error("Could not create XML HTTP Request.");}
	}else{throw new Error("Your browser doesn't support an XML HTTP Request.");}
};
zXmlHttp.isSupported=function(){return zXml.useXmlHttp||zXml.useActiveX;};

//form转字符串
function getRequestBody(oForm) {
	var aParams = new Array();
	var n = oForm.elements.length;
  for (var i=0 ; i < n ; i++) {
		if((oForm.elements[i].type == 'radio')&&(!oForm.elements[i].checked)){continue;}
		var sParam = encodeURIComponent(oForm.elements[i].name);
		sParam += "=" + encodeURIComponent(oForm.elements[i].value);
		aParams.push(sParam);
  } 
  return aParams.join("&"); 
}

//cookie类
function Cookie(){}
Cookie.setCookie=function(name,value,option){
	var str=name+"="+escape(value);
	if(option){
		if(option.expireHours){
			var date=new Date();
			var ms=option.expireHours*3600*1000;
			date.setTime(date.getTime()+ms);
			str+=";expires="+date.toGMTString();
		}
		if(option.path)str += ";path="+option.path;
		if(option.domain)str+=";domain="+option.domain;
		if(option.secure)str+=";true";
	}
	document.cookie=str;
}
Cookie.getCookie=function(name){
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}
Cookie.deleteCookie=function(name){
	this.setCookie(name,'',{expireHours:-1});
}