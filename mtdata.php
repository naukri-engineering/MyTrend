<?php
/*
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
if(!$AUTH) {
    echo "<center><a href='' style='color:#0088CC;text-decoration:none;'>Click here to login</a></center>";
    die;
}
$objMySQL   = new MySQL();
$objMyTrend = new MyTrend($objMySQL);
$objMyStatusVars= new MyStatusVars($objMySQL);

$mysql_id   = $_GET['mysql_id'];
$action     = $_GET['action'];
$database   = $_GET['database'];
$table	    = $_GET['table'];
$date1	    = $_GET['date1'];
$date2	    = $_GET['date2'];
$type	    = $_GET['type'];
$f	    = $_GET['f'];
$v	    = $_GET['v'];
$sizetype   = $_GET['sizetype'];
$clabels    = $_GET['clabels'];
if(!$sizetype) {
    $sizetype 	= 'Gigabyte';
    $convert	= 1024*1024*1024;
}
if($sizetype =='Byte')		$convert=1;
if($sizetype =='Kilobyte')	$convert=1024;
if($sizetype =='Megabyte')	$convert=1024*1024;
if($sizetype =='Gigabyte')	$convert=1024*1024*1024;
switch($action) {

    case 'database':
	$database 	= $objMyTrend->getDatabase($mysql_id);
	echo implode("|*|",$database);
	break;
    case 'table':
	$table 		= $objMyTrend->getTables($mysql_id,$database);
	echo implode("|*|",$table);
	break;
    case 'graph':
	$series = '';
	if($f==1 || $f==2 || $f==3) {
	    if($f==1)
		$graph      = $objMyTrend->getGraph_Instance($mysql_id,$date1,$date2);
	    elseif($f==2)
		$graph      = $objMyTrend->getGraph_Database($mysql_id,$database,$date1,$date2);
	    elseif($f==3)
		$graph      = $objMyTrend->getGraph_Table($mysql_id,$database,$table,$date1,$date2);
	    foreach($graph as $data) {
		if($type == 'rows') {
		    $series .= $data['rows'].",";
		}
		else {
		    $type = 'size';
		    $series .= round($data['data_size']/$convert,4).",";
		}
	    }
	    $series = rtrim($series,",");
	    $dd = explode("-",$date1);
	    $yy = $dd[0];
	    $mm = $dd[1];
	    $dd = $dd[2];
	    $smarty->assign('dd',$dd);
	    $smarty->assign('mm',$mm);
	    $smarty->assign('yy',$yy);
	    $smarty->assign('type',ucwords($type)."  (".$sizetype.")");

	}
	elseif($f==4) {
	    $data = $objMyTrend->getStats_InstanceData($date1);
	    $total = getTotal($data);
	    foreach($data as $val) {
		$text       = $val['mysql_id'];
		$mysqlInstance = $objMyTrend->getMyInstance($text);
		$text = $mysqlInstance[0]['host'].":".$mysqlInstance[0]['port'];
		if($mysqlInstance[0]['name'])
		    $text .= " - ".$mysqlInstance[0]['name'];
		$data_size  = $val['data_size'];
		//$data_size  = round(($val['data_size']/$total)*100,2);

		$series .= "['".$text."',".$data_size."],";
	    }
	    $series = rtrim($series,",");
	}
	elseif($f==5) {
	    $data = $objMyTrend->getStats_DatabaseData($mysql_id,$date1);
	    $total = getTotal($data);
	    foreach($data as $val) {
		$text       = $val['database'];
		$data_size  = $val['data_size'];
		//$data_size  = round(($val['data_size']/$total)*100,2);
		$series .= "['".$text."',".$data_size."],";
	    }
	    $series = rtrim($series,",");
	}
	elseif($f==6) {
	    $data = $objMyTrend->getStats_TableData($mysql_id,$database,$date1);
	    foreach($data as $val) {
		$text       = $val['table'];
		$data_size  = $val['data_size'];
		$series .= "['".$text."',".$data_size."],";
	    }
	    $series = rtrim($series,",");
	}
	elseif($f==7) {
	    $v = trim($v,':');
	    $v = explode(':',$v);
	    if(count($v)==1) {
		$data = $objMyStatusVars->getMySQLVariableStatus($mysql_id,$v[0],$date1,$date2);
		foreach($data as $val) {
		    $series .= $val['value'].",";
		}
		$series = rtrim($series,",");
		$smarty->assign('type',ucwords($v[0]));
	    }
	    else {
		$series_data = array();
		foreach($v as $variable) {
		    $series  = '';
		    $data = $objMyStatusVars->getMySQLVariableStatus($mysql_id,$variable,$date1,$date2);
		    foreach($data as $val) {
			$series .= $val['value'].",";
		    }
		    $series = rtrim($series,",");
		    $series_data[] = array('series'=>$series,'label'=>$variable);
		}
		$smarty->assign("data",$series_data);
		$smarty->assign('select','multiple');
	    }
	    $dd = explode("-",$date1);
	    $yy = $dd[0];
	    $mm = $dd[1];
	    $dd = $dd[2];
	    $smarty->assign('dd',$dd);
	    $smarty->assign('mm',$mm);
	    $smarty->assign('yy',$yy);
	}
	else if($f==8) {
	    $v = trim($clabels,':');
	    $v = explode(':',$v);
	    $objCustomizedGraphs    = new CustomizedGraphs($objMySQL);
	    if(count($v)==1) {
		$data = $objCustomizedGraphs->getData($v[0],$date1,$date2);
		foreach($data as $val) {
		    $series .= $val['value'].",";
		}
		$series = rtrim($series,",");
		$label = $objCustomizedGraphs->getList($v[0]);
		$label = $label[0]['label'];
		$smarty->assign('type',ucwords($label));
	    }
	    else {
		$series_data = array();
		foreach($v as $label) {
		    $series  = '';
		    $data = $objCustomizedGraphs->getData($label,$date1,$date2);
		    foreach($data as $val) {
			$series .= $val['value'].",";
		    }
		    $series = rtrim($series,",");
		    $label = $objCustomizedGraphs->getList($label);
		    $label = $label[0]['label'];
		    $series_data[] = array('series'=>$series,'label'=>$label);
		}
		$smarty->assign("data",$series_data);
		$smarty->assign('select','multiple');
	    }
	    $dd = explode("-",$date1);
	    $yy = $dd[0];
	    $mm = $dd[1];
	    $dd = $dd[2];
	    $smarty->assign('dd',$dd);
	    $smarty->assign('mm',$mm);
	    $smarty->assign('yy',$yy);
	}
	$smarty->assign('series',$series);
	$smarty->assign('f',$f);
	echo $smarty->fetch('graph.html');
	break;
    case 'stats':
	if($f == 1) {
	    $data1 = $objMyTrend->getStats_InstanceData($date1);
	    $data2 = $objMyTrend->getStats_InstanceData($date2);
	    $arrData = array();
	    foreach($data1 as $key=>$data) {
		$size1      = round($data1[$key]['data_size']/$convert,4);
		$size2      = round($data2[$key]['data_size']/$convert,4);
		if($size1) {
		    $sChange    = round((($size2-$size1)/$size1)*100,2);
		    $change  = round($size2-$size1,3);
		}
		else {
		    $sChange    = 0;
		    $change = 0;
		}
		$mysqlInstance = $objMyTrend->getMyInstance($key);
		$Instance = $mysqlInstance[0]['host'].":".$mysqlInstance[0]['port'];
		if($mysqlInstance[0]['name'])
		    $Instance .= " - ".$mysqlInstance[0]['name'];
		$arrData[] = array("mysql_id"=>$mysqlInstance[0]['mysql_id'],"instance"=>$Instance,"size1"=>$size1,"size2"=>$size2,"sizechange"=>$sChange,"change"=>$change);
	    }
	}
	elseif($f == 2) {
	    $data1 = $objMyTrend->getStats_DatabaseData($mysql_id,$date1);
	    $data2 = $objMyTrend->getStats_DatabaseData($mysql_id,$date2);
	    $arrData = array();
	    foreach($data1 as $key=>$data) {
		$size1      = round($data1[$key]['data_size']/$convert,4);
		$size2      = round($data2[$key]['data_size']/$convert,4);
		if($size1) {
		    $sChange    = round((($size2-$size1)/$size1)*100,2);
		    $change  = round($size2-$size1,3);
		}
		else {
		    $sChange    = 0;
		    $change = 0;
		}
		$arrData[] = array("mysql_id"=>$mysql_id,"database"=>$key,"size1"=>$size1,"size2"=>$size2,"sizechange"=>$sChange,"change"=>$change);
	    }
	}
	elseif($f == 3) {
	    $data1 = $objMyTrend->getStats_TableData($mysql_id,$database,$date1);
	    $data2 = $objMyTrend->getStats_TableData($mysql_id,$database,$date2);
	    $arrData = array();
	    foreach($data1 as $key=>$data) {
		$rows1 		= $data1[$key]['rows'];
		$rows2 		= $data2[$key]['rows'];
		if($rows1)
		    $rChange	= round((($rows2-$rows1)/$rows1)*100,2);
		else
		    $rChange    = 0;
		$size1      = round($data1[$key]['data_size']/$convert,4);
		$size2      = round($data2[$key]['data_size']/$convert,4);
		if($size1) {
		    $sChange    = round((($size2-$size1)/$size1)*100,2);
		    $change  = round($size2-$size1,3);
		}
		else {
		    $sChange    = 0;
		    $change = 0;
		}
		$arrData[] = array("mysql_id"=>$mysql_id,"database"=>$database,"table"=>$key,"rows1"=>$rows1,"rows2"=>$rows2,"size1"=>$size1,"size2"=>$size2,"rowschange"=>$rChange,"sizechange"=>$sChange,"change"=>$change);
	    }
	}
	elseif($f==7) {
	    $data1 = $objMyStatusVars->getCompleteStatus($v,$date1);
	    $data2 = $objMyStatusVars->getCompleteStatus($v,$date2);
	    $arrData = array();
	    foreach($data1 as $mysql_id=>$data) {
		$mysqlInstance = $objMyTrend->getMyInstance($mysql_id);
		$Instance = $mysqlInstance[0]['host'].":".$mysqlInstance[0]['port'];
		if($mysqlInstance[0]['name'])
		    $Instance .= " - ".$mysqlInstance[0]['name'];
		$value1     = $data['value'];
		$value2     = $data2[$mysql_id]['value'];
		if($value1) {
		    $sChange = round((($value2-$value1)/$value1)*100,2);
		    $change = round($value2-$value1,3);
		}
		else {
		    $sChange = 0;
		    $change = 0;
		}
		$arrData[] = array("mysql_id"=>$mysql_id,"instance"=>$Instance,"value1"=>$value1,"value2"=>$value2,"per_change"=>$sChange,"change"=>$change);
	    }
	    $smarty->assign('v',$v);
	}
	$smarty->assign('f',$f);
	$smarty->assign('sizetype',$sizetype);
	$smarty->assign('date1',$date1);
	$smarty->assign('date2',$date2);
	$smarty->assign('data',$arrData);
	echo $smarty->fetch('statsData.html');
}
function getTotal($data) {
    $total = 0;
    foreach($data as $val) {
	$total += $val['data_size'];
    }
    return $total;
}
?>
