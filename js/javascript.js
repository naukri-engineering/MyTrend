/* ----------- STATUS TREND | START ----------- */
function getGraph_Status(divId,mysql_id,v,date1,date2,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var oSelect=gbi('v');	
    var count=0;
    var v = '';
    for(var i=0;i<oSelect.options.length;i++) {
	if(oSelect.options[i].selected) {
	    v = v + oSelect.options[i].value + ':';
	    count++;
	}
    }
    var url = "mtdata.php?v="+v+"&mysql_id="+mysql_id+"&action=graph&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'graph',divId);
}
function getStats_Status(divId,v,date1,date2,f,hide) {
    if(hide=='hide') {
	if(!validation('stats',f))
	    return false;
    }
    gbi('data1').style.display='';gbi('data1Clear').innerHTML='<br/><br/>';
    gbi('data2').style.display='none';
    gbi('data3').style.display='none';
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?action=stats&v="+v+"&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'stats',divId);
}


/* ----------- STATUS TREND | END   ----------- */
/* ----------- GRAPH DISTRIBUTION | START ----------- */
function getGraph_InstanceDistribution(divId,date1,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?action=graph&date1="+date1+"&f="+f;
    ajax(url,'graph',divId);
}
function getGraph_DatabaseDistribution(divId,mysql_id,date1,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?mysql_id="+mysql_id+"&action=graph&date1="+date1+"&f="+f;
    ajax(url,'graph',divId);
}
function getGraph_TableDistribution(divId,mysql_id,database,date1,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?mysql_id="+mysql_id+"&action=graph&database="+database+"&date1="+date1+"&f="+f;
    ajax(url,'graph',divId);
}
/* ----------- GRAPH |   END ----------- */


/* ----------- GRAPH TREND | START ----------- */
function getGraph_Instance(divId,sizetype,mysql_id,date1,date2,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?sizetype="+sizetype+"&mysql_id="+mysql_id+"&action=graph&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'graph',divId);
}
function getGraph_Database(divId,sizetype,mysql_id,database,date1,date2,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?sizetype="+sizetype+"&mysql_id="+mysql_id+"&action=graph&database="+database+"&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'graph',divId);
}
function getGraph_Table(divId,sizetype,mysql_id,database,table,type,date1,date2,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var url="mtdata.php?sizetype="+sizetype+"&mysql_id="+mysql_id+"&action=graph&database="+database+"&table="+table+"&date1="+date1+"&date2="+date2+"&type="+type+"&f="+f;
    ajax(url,'graph',divId);
}
function getGraph_clabels(divId,clabels,date1,date2,f) {
    if(!validation('graph',f))
	return false;
    gbi(divId+'Load').style.display='';
    var oSelect=gbi('clabels');
    var count=0;
    var v = '';
    for(var i=0;i<oSelect.options.length;i++) {
	if(oSelect.options[i].selected) {
	    v = v + oSelect.options[i].value + ':';
	    count++;
	}
    }
    var url="mtdata.php?action=graph&clabels="+v+"&date1="+date1+"&date2="+date2+"&type="+type+"&f="+f;
    ajax(url,'graph',divId);
}
/* ----------- GRAPH |   END ----------- */

/* ------------ STATS | START ----------- */
function getStats_InstanceData(divId,sizetype,date1,date2,f,hide) {
    if(hide=='hide') {
	if(!validation('stats',f))
	    return false;
    }
    gbi('data1').style.display='';gbi('data1Clear').innerHTML='<br/><br/>';
    gbi('data2').style.display='none';
    gbi('data3').style.display='none';
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?action=stats&sizetype="+sizetype+"&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'stats',divId);
}

function getStats_DatabaseData(divId,mysql_id,sizetype,date1,date2,f,hide) {
    if(hide=='hide') {
	if(!validation('stats',f))
	    return false;
    }
    gbi('data2').style.display='';gbi('data2Clear').innerHTML='<br/><br/>';
    if(hide == 'hide') {
	gbi('data1').style.display='none';
	gbi('data3').style.display='none';
    }
    else {
	gbi('data3').style.display='none';
    }
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?mysql_id="+mysql_id+"&action=stats&sizetype="+sizetype+"&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'stats',divId);
}
function getStats_TableData(divId,mysql_id,database,sizetype,date1,date2,f,hide) {
    if(hide=='hide') {
	if(!validation('stats',f))
	    return false;
    }
    gbi('data3').style.display='';gbi('data3Clear').innerHTML='<br/><br/>';
    if(hide == 'hide') {
	gbi('data1').style.display='none';
	gbi('data2').style.display='none';
    }
    gbi(divId+'Load').style.display='';
    var url = "mtdata.php?mysql_id="+mysql_id+"&action=stats&database="+database+"&sizetype="+sizetype+"&date1="+date1+"&date2="+date2+"&f="+f;
    ajax(url,'stats',divId);
}
/* ------------ STATS |  END ----------- */

function getDatabase(mysql_id,id) {
    gbi(id+'Load').style.display='';
    var url = "mtdata.php?mysql_id="+mysql_id+"&action=database";
    //gbi(id).options.length = 0;
    //gbi(id).options[0] = new Option('Select Database:',-1);
    ajax(url,'database',id);
}
function getTable(mysql_id,database,id) {
    gbi(id+'Load').style.display='';
    var url = "mtdata.php?mysql_id="+mysql_id+"&action=table&database="+database;
    ajax(url,'table',id);
}
function ajax(url,action,id) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
	xmlhttp=new XMLHttpRequest();
    }
    else {
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	    switch(action) {
		case 'database':
		    generateDropDown(id,'Select Database:',xmlhttp.responseText);
		    gbi(id+'Load').style.display='none';
		    break;
		case 'table':
		    generateDropDown(id,'Select Table:',xmlhttp.responseText);
		    gbi(id+'Load').style.display='none';
		    break;
		case 'graph':
		    gbi(id).innerHTML = xmlhttp.responseText;
		    eval(gbi('chartscript').innerHTML);
		    gbi(id+'Load').style.display='none';
		    window.location.hash = '#'+id;
		    break;
		case 'stats':
		    gbi(id).innerHTML = xmlhttp.responseText;
		    eval(gbi(id+'script').innerHTML);
		    gbi(id+'Load').style.display='none';
		    window.location.hash = '#'+id;
		    break;
	    }
	}
    }
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}
function generateDropDown(id,firstLabel,responseText) {
    var arr = responseText.split("|*|");
    gbi(id).options.length = 0;
    var newOpt = new Option(firstLabel,-1);
    gbi(id).options[0] = newOpt;
    if(responseText!='') {
	for(var i=0;i<arr.length;i++) {
	    var newOpt = new Option(arr[i],arr[i]);
	    gbi(id).options[i+1] = newOpt;
	}
    }
}
function validation(page,f) {
    var success = true;
    var errorcolor = '#F5BABA';
    var defaultcolor = '#EEEEEE';

    if((page=='server-config' && f==1) || (page=='changelog' && f==2)) {
	gbi('instanceRow').style.background = defaultcolor;
	var oSelect=gbi('instance');
	var count=0;
	for(var i=0;i<oSelect.options.length;i++){
	    if(oSelect.options[i].selected) {
		count++;
	    }
	}
	if(count==0) {
	    success = false;
	    gbi('instanceRow').style.background = errorcolor;
	}
	if((page=='server-config' && f==1)) {
	    return success;
	}
    }
    if(page=='processlist' || (page=='changelog' && f==1)) {
	gbi('instanceRow').style.background = defaultcolor;
	if(gbi('instance').value=='-1') {
	    success = false;
	    gbi('instanceRow').style.background = errorcolor;
	}
	if(page=='processlist') {
	    return success;	
	}
    }
    if((page=='stats' && (f==2 || f==3)) || (page=='graph' && (f==1 || f==2 || f==3 || f==5 || f==6 || f==7)) || (page=='server-config' && f==2)) {
	gbi('instanceRow').style.background = defaultcolor;
	if(gbi('instance').value=='-1') {	
	    success = false;
	    gbi('instanceRow').style.background = errorcolor;
	}
    }
    if((page=='stats' && f==3) || (page=='graph' && (f==2 || f==3 || f==6))) {
	gbi('databaseRow').style.background = defaultcolor;
	if(gbi('database').value=='-1') {	
	    success = false;
	    gbi('databaseRow').style.background = errorcolor;
	}
    }
    //Change Log
    if(page=='changelog' && f==1) {
	gbi('logRow').style.background = defaultcolor;
	if(gbi('log').value=='') {
	    success = false;
	    gbi('logRow').style.background = errorcolor;
	}
    }
    if(page=='graph' && f==8) {
	gbi('clabelsRow').style.background = defaultcolor;
	var oSelect=gbi('clabels');
	var count=0;
	var v = '';
	for(var i=0;i<oSelect.options.length;i++) {
	    if(oSelect.options[i].selected) {
		count++;
	    }
	}
	if(count==0) {
	    success = false;
	    gbi('clabelsRow').style.background = errorcolor;
	}
    }
    //Variable
    if((page=='stats' && f==7) || (page=='graph' && f==7)) {
	gbi('vRow').style.background = defaultcolor;
	var oSelect=gbi('v');
	var count=0;
	var v = '';
	for(var i=0;i<oSelect.options.length;i++) {
	    if(oSelect.options[i].selected) {
		count++;
	    }
	}
	if(count==0) {
	    success = false;
	    gbi('vRow').style.background = errorcolor;
	}
    }
    if(page=='graph' && f==3) {
	gbi('tableRow').style.background = defaultcolor;
	if(gbi('table').value=='-1') {	
	    success = false;
	    gbi('tableRow').style.background = errorcolor;
	}
    }
    try {
	gbi('date1Row').style.background = defaultcolor;
    }
    catch(e) {}
    if(gbi('date1').value=='') {	
	success = false;
	gbi('date1Row').style.background = errorcolor;
    }
    if((page=='stats' && (f==1 || f==2 || f==3 || f==7)) || (page=='graph' && (f==1 || f==2 || f==3 || f==7 || f==8)) || (page=='server-config' && f==2) || (page=='changelog' && f==2)) {
	gbi('date2Row').style.background = defaultcolor;
	if(gbi('date2').value=='') {	
	    success = false;
	    gbi('date2Row').style.background = errorcolor;
	}
    }
    return success;	
}
function gbi(element) {return document.getElementById(element); }


$(document).ready(function(){
    $('.lb_close').click(function(){
        $('.lb_bak, .lb_box').animate({'opacity':'0'}, 0, 'linear', function(){$('.lb_bak, .lb_box').css('display', 'none');});
    });
});

function lightbox_adduser() {
    $('#adduserForm')[0].reset();
    $('#lb_bak_adduser, #lb_box_adduser').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_adduser').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_adduser, #lb_box_adduser').css('display', 'block');
}
function lightbox_server_group(username) {
    $('#server_groupForm')[0].reset();
    $('#lb_bak_server_group, #lb_box_server_group').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_server_group').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_server_group, #lb_box_server_group').css('display', 'block');
    var config = {
      '.chzn-select'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    $('#server_groupFormUsername').val(username);
}
function lightbox_password(username) {
    $('#passwordForm')[0].reset();
    $('#lb_bak_password, #lb_box_password').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_password').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_password, #lb_box_password').css('display', 'block');
    $('#passwordFormUsername').val(username);
    $('#usernameLabel').html(username);
}
function lightbox_delete(url) {
    $('#lb_bak_delete, #lb_box_delete').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_delete').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_delete, #lb_box_delete').css('display', 'block');
    $('#url_delete').attr('href',url);
}
function lightbox_close() {
    $('.lb_bak, .lb_box').animate({'opacity':'0'}, 0, 'linear', function(){$('.lb_bak, .lb_box').css('display', 'none');});
}
