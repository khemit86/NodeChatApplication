<?php
require_once('../../master-config-app.php');
	$staffDataResult = array();
	$staffIds = "";
	
	$conn 		= 		new mysqli($commonDriverDatabases[0],$commonDriverDatabases[1],$commonDriverDatabases[2],$commonDriverDatabases[3]);
	
	$GetStaffListingSql	=	'SELECT "WLR" AS selectedDB,Customer.CustID ';
	$GetStaffListingSql.=	'FROM customers AS Customer ';
	$GetStaffListingSql.=	'LEFT JOIN cust_types AS CustType ON( CustType.CustTypeID = Customer.CustTypeID) ';
	$GetStaffListingSql.= "WHERE CustType.CustTypeID='1' ";
	$rs		=	$conn->query($GetStaffListingSql);
	
		//initiate the database connection 
		if($conn->connect_error) {
		}
	while($row = $rs->fetch_assoc())
	{
		
		
		$staffDataResult[] = $row['CustID']."|".$row['selectedDB'];
	}
	if(!empty($staffDataResult))
	{
		$staffIds = implode(",",$staffDataResult);
	}
	//pr($staffDataResult);




?>