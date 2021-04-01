<?php
/**
 * This script takes input as TABLE Index, FieldName, ValueFormat and LabelFormat. and return jsonp data 
 * Use for Autocomplete only
 */
require("../configure.php");
echo $_GET['callback'].  "(";


if ($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['term']))
{
	echo json_encode(array("Error"=> "Unauthorize access!!!"));
} else {
	if($db->Connect()) {
		$term = DoSecure($_POST['term']);
		$tablename = DoSecure($_POST['source']);
		$field = DoSecure($_POST['search']);
		$returnvalue = DoSecure($_POST['returnValue']);
		$returnLabel = DoSecure($_POST['returnLabel']);
		$condition = (isset($_POST['condition']) && trim(DoSecure($_POST['condition']))!=""?DoSecure($_POST['condition']):'');		
		if(isset($GLOBALS['Tables'][$tablename])) {
			$condition = PrepareContent($condition, $_SESSION);
			$condition = ($condition !="" ? " and $condition":"");
			$sql = "Select * from ". $GLOBALS['Tables'][$tablename] . " where " .$field ." like '%" . $term . "%' $condition";
			$rs = $db->Execute($sql);
			if ($db->RowCount($rs)> 0) {
				$out = array();
				while($row = $db->Fetch($rs)){
					$out[] = array( "label" => PrepareContent($returnLabel, $row), "value" => PrepareContent($returnvalue, $row), "complete"=> $row);
				}
				echo json_encode($out);
			} else {
				echo json_encode(array("Error"=> "norecord"));	
			}
		} else {
			echo json_encode(array("Error"=> "Incorrect Data!!!"));
		}		
	} else {
		echo json_encode(array("Error"=> "Database error"));	
	}
}
echo ")";
?>