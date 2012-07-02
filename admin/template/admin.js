// JavaScript Document
function GetCurrentStyle (obj, prop) {     
    if (obj.currentStyle) {
        return obj.currentStyle[prop];     
    }
    else if (window.getComputedStyle) {
        propprop = prop.replace (/([A-Z])/g, "-$1");           
        propprop = prop.toLowerCase ();        
         return document.defaultView.getComputedStyle (obj,null)[prop];     
    }      
    return null;
}
function menu_chang(s){
	var dd=document.getElementById(s);
	dd=GetCurrentStyle(dd,"display");
	if(dd=='none')
		document.getElementById(s).style.display="inline";
	else document.getElementById(s).style.display="none";
}

function menu_show(s){
	document.getElementById(s).style.display="inline";
}
function menu_hide(s){
	document.getElementById(s).style.display="none";
}

function selectAll(select)
{
	with (document.tableform)
	{
		var checkval = false;
		var i=0;
 
		for (i=0; i< elements.length; i++)
			if (elements[i].type == 'checkbox' && !elements[i].disabled)
				if (elements[i].name.substring(0, select.length) == select)
				{
					checkval = !(elements[i].checked);	break;
				}
 
		for (i=0; i < elements.length; i++)
			if (elements[i].type == 'checkbox' && !elements[i].disabled)
				if (elements[i].name.substring(0, select.length) == select)
					elements[i].checked = checkval;
	}
}
function checkbox_change(tablename,id){
	chk = document.getElementById(id);
	if(chk.checked==false)chk.checked=true;
	else chk.checked=false;
}

function error_notic(err,url){
	alert(err);
	go_to_last_page(url);
}
function go_to_last_page(url){
	if(confirm("你是否返回?")){
		if(url=='')
			history.back(-1);
		else
			self.location=url;
	}
}
function id_show(s){
	document.getElementById(s).style.display="inline";
}
function id_hide(s){
	document.getElementById(s).style.display="none";
}
function setDay(obj)
{
	obj = obj.form; 
	var years=parseInt(obj.years.options[obj.years.selectedIndex].value);
	var months=parseInt(obj.months.options[obj.months.selectedIndex].value);
	if(obj.years.selectedIndex==0 || obj.months.selectedIndex==0)return; 
	var lastday = monthday(years,months);
	var itemnum = obj.days.length; 
	if (lastday - 1 < obj.days.selectedIndex) {   
		obj.days.selectedIndex = lastday - 1; 
	}
	obj.days.length = lastday; 
	for(cnt = itemnum + 1;cnt <= lastday;cnt++) {
		obj.days.options[cnt - 1].text = cnt; 
	}
}
function monthday(years,months){
	var lastday = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
	if (((years % 4 == 0) && (years % 100 != 0)) || (years % 400 == 0)){ 
		lastday[1] = 29; 
	} 
	return lastday[months - 1];
}
function forto(ff,to){
	document.write('<OPTION value=""></OPTION>'); 
	for(var ii=ff; ii<=to; ii++) 
		document.write('<OPTION value="'+ii+'">'+ii+'</OPTION>');       
}