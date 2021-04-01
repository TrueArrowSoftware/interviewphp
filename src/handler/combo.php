<?php
require("../configure.php");
header("Content-Type: application/json", true);
$json = array();
if ($db->Connect() && isset($Tables[DoSecure($_GET['table'])]))
{
	$where ='';
	if(isset($_SESSION['user'])) {
		require_once("../include/player-function.php");
	} else if(isset($_SESSION['coach'])){
		require_once("../include/coach-function.php");		
		$f = DB::GetColumns(DoSecure($_GET['table']));
		foreach($f as $i=>$v){
			if($v =='coachid'){
				$where = ' coachid='. $coach->CoachID ;
				break;
			}
		}
	}
	$where .= ($where!=''?' and ':'') . mysql_escape_string(DoSecure($_GET['valuefield'])) . " ='" . mysql_escape_string($_GET['value']) . "'"; 
	$rs = $db->DBRecordSet(DoSecure($_GET['table']), '', $where );
	if ($db->RowCount($rs) > 0)
	{
		$fields = explode("|", DoSecure($_GET['field']));		
		while ($row = $db->FetchArray($rs))
		{			
			$json[$row[trim($fields[0])]] = PrepareContent($fields[1], $row);		
		}
	}
}
echo json_encode($json);
?>