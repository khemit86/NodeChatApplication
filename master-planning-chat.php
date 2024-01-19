<?php 	
require_once("master-planning-header.php");

//echo $_SESSION['Auth']['Customer']['CustID'];
$link = mysql_connect($commonDriverDatabases[0], $commonDriverDatabases[1], $commonDriverDatabases[2]);

mysql_select_db($commonDriverDatabases[3], $link);
//initiate the database connection 


$CompanyId = 'WLR';
$dbrow = $alldatabases[$CompanyId];
// get the array of database regarding compnay
$conn_web = mysql_connect($dbrow[0], $dbrow[1], $dbrow[2]);
mysql_select_db($dbrow[3], $conn_web);

$GetDriverSql = "SELECT Driver.DrvName,Driver.DrvLastName,Driver.DrvProfilePic, Driver.DrvID,Driver.DrvDeviceToken FROM " . $commonDriverDatabases[3] . ".drivers_central AS Driver WHERE Driver.DrvActive = 'YES' order BY CASE 
WHEN Driver.DrvDeviceToken !='' THEN 1 ELSE 0 END DESC,Driver.DrvName ASC";
	 
    $resultDriver = mysql_query($GetDriverSql);
    $totalRecord = mysql_num_rows($resultDriver);
	
    //$rowDriverData = $resultDriver->fetch_assoc();
    //$rowDriverData = mysql_fetch_array($resultDriver,MYSQL_ASSOC);
	/* while ($rowDriverData = mysql_fetch_array($resultDriver,MYSQL_ASSOC)) {
	echo "<pre>";
	print_r($rowDriverData);
	} */

// getting staff data here
    
    ################ connection for staff #########

    $GetStaffDetailSql = "SELECT Customer.CustName  ";
    $GetStaffDetailSql.= "FROM " . $dbrow[3] . ".customers As Customer WHERE Customer.CustTypeID = '1' AND Customer.CustID = '" . $_SESSION['Auth']['Customer']['CustID'] . "' ";

    $resultStaff = mysql_query($GetStaffDetailSql);
    $totalStaffRecord = mysql_num_rows($resultStaff);
    //$rowStaffData = $resultStaff->fetch_assoc();
    $rowStaffData = mysql_fetch_array($resultStaff,MYSQL_ASSOC);
	$userName = $rowStaffData['CustName'];
    $image = SITE_URL . "planning/driver-profile-picture/no_image.jpg";
	$staffId = $_SESSION['Auth']['Customer']['CustID'];
    $type = 'staff';





	
//die('DFDFDFD');
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<link href="scripts/style.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="scripts/jquery.multiselect.css" />
	<link rel="stylesheet" type="text/css" href="scripts/prettify.css" />
	<link rel="stylesheet" type="text/css" href="scripts/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="scripts/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="scripts/css/fancybox/jquery.fancybox-1.3.4.css" media="screen" />	
	<script type="text/javascript" src="scripts/jquery1.11.1.min.js"></script>
	<script type="text/javascript" src="scripts/jquery-migrate-1.0.0.js"></script>
	<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
	<script type="text/javascript" src="scripts/prettify.js"></script>
	<script type="text/javascript" src="scripts/jquery.multiselect.js"></script>
	<!--<script type="text/javascript" src="scripts/jquery-ui-timepicker-addon.js"></script>-->
	<script type="text/javascript" src="scripts/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	
	<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>
	<script src="https://booking.wolero.com/planning/chat/public/js/moment.min.js"></script>
	 <script src="https://wolero.com:4000/socket.io/socket.io.js"></script>
	 
	
	<style>
		.ui-multiselect-close{display:none;}
		select {width:100px;font-size:10px;}		
	</style>
	<style type="text/css">
        .web_dialog_overlay
        {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            background: #000000;
            opacity: .15;
            filter: alpha(opacity=15);
            -moz-opacity: .15;
            z-index: 101;
            display: none;
        }
        .web_dialog
        {
            display: none;
            position: fixed;
            width: 380px;
            height: 200px;
            top: 50%;
            left: 50%;
            margin-left: -190px;
            margin-top: -100px;
            background-color: #ffffff;
            border: 2px solid #336699;
            padding: 0px;
            z-index: 102;
            font-family: Verdana;
            font-size: 10pt;
        }
        .web_dialog_title
        {
            border-bottom: solid 2px #336699;
            background-color: #336699;
            padding: 4px;
            color: White;
            font-weight:bold;
        }
        .web_dialog_title a
        {
            color: White;
            text-decoration: none;
        }
        .align_right
        {
            text-align: right;
        }
    </style>
</head>
<body>	
	<script type="text/javascript">
	var mywindow;
	</script>
	<style>

	 /* new css added 21-1-2016*/
	 /* @import url(http://weloveiconfonts.com/api/?family=fontawesome|iconicstroke|typicons);*/
	   /* fontawesome */
	   [class*="fontawesome-"]:before {
		font-family: 'FontAwesome', sans-serif;
	  }
	  /* iconicstroke */
	  [class*="iconicstroke-"]:before {
		 font-family: 'FontAwesome';
	  }
	  /* typicons */
	  [class*="typicons-"]:before {
		 font-family: 'FontAwesome';
	  }
	 
	  .chat-container {
		width: 300px;
		position:fixed;
		bottom:10px;
		/* right:310px; */
	  }
	  
	  
	   .chat-container-2 {
		width: 300px;
		position:fixed;
		bottom:10px;
		right:620px;
	  }
	  
	   .chat-container-3 {
		width: 300px;
		position:fixed;
		bottom:10px;
		right:930px;
	  }
	  
	  .top-header {
		background: #6385ae;
		color: #fff;
		padding: 0.2rem;
		position: relative;
		overflow: hidden;
		border-bottom: 4px solid #4a6484;
	  }
	  .top-header:hover {
		background-color:#6385ae;
	  }
	  .top-header-tit {
		display: inline;
		font-size: 14px;
		margin-left: 4px;
	  }
	  .top-header .typicons-message {
		display: inline-block;
		padding: 2px 5px 2px 5px;
		font-size: 20px;
		position: relative;
		top: 5px;
	  }
	  .top-header .typicons-minus {
		position: relative;
		top: 3px;
		font-size: 15px;
		cursor:pointer;
	  }
	  .top-header .typicons-times{
		position: relative;
		top: 3px;
		font-size: 15px;
		cursor:pointer;
	  }
	  .top-header .left {
		float: left;
		padding:3px 0 0 8px;
	  }
	  .top-header .right {
		float: right;
		padding-top: 0px;
	  }
	  .top-header .right img{
	  padding-left:10px;
	  
	  }
	  .top-header > * {
		position: relative;
	  }
	  .top-header::before {
		content: '';
		position: absolute;
		top: -100%;
		left: 0;
		right: 0;
		bottom: -100%;
		opacity:1;
		background-color: #6385ae 
	  }
	  .chat-box-wolero {
		list-style: none;
		background: #e5e5e5;
		margin: 0;
		padding: 0 0 50px 0;
		height: 234px;
		overflow-y: auto;
	  }
	  .chat-box-wolero li {
		padding: 0.5rem;
		overflow: hidden;
		display: flex;
	  }
	  .chat-box-wolero .avatar-icon {
		width: 40px;
		position: relative;
	  }
	  .chat-box-wolero .avatar-icon img {
		display: block;
		width: 100%;
		background-color:#1469A6;
	  }
	  .another .avatar-icon:after {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		width: 0;
		height: 0;
		border: 5px solid white;
		border-left-color: transparent;
		border-bottom-color: transparent;
	  }
	  .me {
		justify-content: flex-end;
		align-items: flex-end;
	  }
	  .me .messages {
		order: 1;
		border-bottom-right-radius: 0;
	  }
	  .me .avatar-icon {
		order: 2;
	  }
	  .me .avatar-icon:after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 0;
		height: 0;
		border: 5px solid white;
		border-right-color: transparent;
		border-top-color: transparent;
		box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
	  }
	  .messages {
		background: white;
		padding: 10px;
		border-radius: 2px;
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
		width:210px;
	  }
	  .messages p {
		font-size:13px;
		margin: 0 0 0.2rem 0;
		word-wrap: break-word;
	  }
	  .messages time {
		font-size: 10px;
		color: #ccc;
	  }
	  .setting{
		background-color: #e5e5e5;
		height: 32px;
		padding-top: 2px;
		border-bottom: 1px solid rgba(0,0,0,.1);
	  }
	  .setting .left {
		float: left;
	  }
	  .setting .right {
		float: right;
	  }
	  .iconicstroke-user{
	   font-size: 22px;
	   position: relative;
	   top: 4px;
	   left: 10px;
	   color: #414141;
	   cursor:pointer;
	 }
	 .typicons-cog{
	   font-size: 23px;
	   position: relative;
	   top: 7px;
	   right: 4px;
	   color: #414141;
	   cursor:pointer;
	 }
	 .fontawesome-facetime-video{
	   font-size: 18px;
	   position: relative;
	   top: 3px;
	   color: #414141;
	   left: 5px;
	   cursor:pointer;
	 }
	 .iconicstroke-user:hover, .typicons-cog:hover,.fontawesome-facetime-video:hover{
	  color:#000000;
	}
	::-webkit-scrollbar{height:14px;width:10px;background:#eee;border-left:1px solid #ddd}
	::-webkit-scrollbar-thumb{background:#ddd;border:1px solid #cfcfcf}
	::-webkit-scrollbar-thumb:hover{background:#b2b2b2;border:1px solid #b2b2b2}
	::-webkit-scrollbar-thumb:active{background:#b2b2b2;border:1px solid #b2b2b2}
	@-webkit-keyframes pulse {
	  from {
		opacity: 0;
	  }
	  to {
		opacity: 0.5;
	  }
	}

	textarea.textarea-box{
	width:224px;
	height:40px;
	border:1px solid #cfcfcf;
	float:left;
	resize:none;

	}
	a.send-btn{
	background:#6385ae;
	font-size:14px;
	text-align:center;
	padding:10px 12px 10px 12px;
	display:inline-block;
	border-radius:4px;
	color:#fff;
	margin-top:8px;
	font-weight:bold;
	margin-left:8px;

	}

	a.send-btn:hover{
	text-decoration:none;

	}


	.chat-active{    
	width: 8px;
		height: 8px;
	   display: inline-block;
		-moz-border-radius: 100%;
		-webkit-border-radius: 100%;
		-khtml-border-radius: 100%;
		border-radius: 100%;
	   
		border: 1px solid #fff;
		}
		div.chat-mid-list-rw
		{
			cursor:pointer;
		}

	</style>


	
	 <div id="output"></div>
    
    <div id="overlay" class="web_dialog_overlay"></div>
	
	
	
	 
	  <div id="dialog" class="web_dialog">
		<form id='remsaveform'>
	  <table style="width: 100%; border: 0px;" cellpadding="3" cellspacing="0">
            <tr>
                <td class="web_dialog_title">Add Job Internal Remarks</td>
                <td class="web_dialog_title align_right">
                    <a href="#" id="btnClose">Close</a>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
			<tr>
              <td colspan="2" style="text-align: center;">
             <textarea id='remint' placeholder='New' name='jobintrem' style="height: 95px;resize: none;width: 347px;"></textarea>
			 <input type='hidden' name='jobid' id='remjobid'/>
			 <input type='hidden' name='selectedDB' id='remjobdb' />
			 </td>
            </tr>
			<tr>
                <td colspan="2" style="text-align: center;">
                    <input id="btnSubmit" type="button" value="Submit" />
                </td>
            </tr>
			</table>
			</form>
	  </div>
	<div class="wraper">
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td style="padding:10px 0;" align="center">		 
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="center" width="14.28%;" style="padding-left:40px;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg" value="Show All" onclick="showAll();" /></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg" value="Take Snapshot" onclick="OpenInNewTab('<?php echo getTargetPdfUrl(); ?>','pdf=4');"  /></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg" value="Print master list" onclick="toAction('<?php echo getTargetPdfUrl(); ?>','pdf=1');"  /></span></td>							
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg" value="Print signage" onclick="toAction('<?php echo getTargetPdfUrl(); ?>','pdf=3');" /></span></td>							
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg" value="Print jobsheet" onclick="toAction('<?php echo getTargetPdfUrl(); ?>','pdf=2');" /></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg" value="Close" onclick="window.location='<?php echo dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])))); ?>/jobs/billlink'"/></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Sort on Customer" onclick="toAction('<?php echo getTargetUrl('sort');?>','sort=cname');" /></span></td>	
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Sort on Driver" onclick="toAction('<?php echo getTargetUrl('sort');?>','sort=drv');" /></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Sort on Job Time" onclick="toAction('<?php echo getTargetUrl('sort');?>','sort=jtime');" /></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Send All SMS" onclick="sendAllSMS();" /></span></td>
							<td align="center" width="14.28%;"><span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Send Driver Details" onclick="sendAllDriverSMS();" /></span></td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="29%">			
								<div id="companyContainer">
									<select  class="HeadSeFild" id="company" multiple="multiple">			 					
										<?php  
												
											foreach($alldatabasesOnly as $onedb){
												if(in_array($onedb,$selectedDatabase)){
													$selected	=	'selected';
												}else{
													$selected	=	'';
												}
												echo "<option ".$selected." value='".$onedb."'>".$onedb."</option>";	
											}	
										?>
									</select>
								</div>
							</td>
							<td width="31%">
								<span style="color:#fff; font-size: 35px;">MASTER -PLANNING</span>
							</td>
							<td width="31%">								
								<input style="float:left;margin-top:4px;" class="HeadSeFild datepicker" <?php if(isset($_GET['date']) && !empty($_GET['date']) ){ echo 'value="'.$_GET['date'].'"'; } ?> id="search" name="search" type="text" readonly="readonly" />							
								<span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Submit" onclick="searchData();" /></span>
								<span class="BlkBtnLeBg"><input name="" type="button" class="BlkBtnRiBg WidthFix" value="Send Notification All Drivers" onclick="sendNotificationAllDriver();" /> 
								
							</td>
							<td>&nbsp;</td>
							<td align="right" width="30%">
								<div id="vehiclesContainer">
									<select  class="HeadSeFild" id="vehicles" multiple="multiple">			 					
										<?php  
												
											foreach($allVehicles as $onedbV){
												if(in_array($onedbV,$selectedVehicles)){
													$selected	=	'selected';
												}else{
													$selected	=	'';
												}
												echo "<option ".$selected." value='".$onedbV."'>".$onedbV."</option>";	
											}	
										?>
									</select>
								</div>
								<script>
									//$(function(){
											$("#company").multiselect({
												 close: function(event, ui){
												 //alert(1);
													//console.log(event.target);
													var selectedCompany = new Array();				
													$("input[name=multiselect_company]:checked").each(function(i){
															selectedCompany.push(this.value);
													});
													selectedCompany			=		selectedCompany.join(",");
													toAction('<?php echo getTargetUrl('company');?>','company='+ selectedCompany );
													
												},
												selectedText:"Show records from following Companies",
												noneSelectedText:"Select Company",
												height:'auto',
												minWidth:280,
												classes:'companyDD'
											});
											$("#vehicles").multiselect({
												 
												 close: function(event, ui){ 
												 //alert(2);
													var selectedCompany = new Array();				
													$("input[name=multiselect_vehicles]:checked").each(function(i){
															selectedCompany.push(this.value);
													});
													selectedCompany			=		selectedCompany.join(",");
													toAction('<?php echo getTargetUrl('vehicles');?>','vehicles='+ selectedCompany );
													
												},
												selectedText:"Show records from following vehicles",
												noneSelectedText:"Select Company",
												height:'auto',
												minWidth:265,
												classes:'vehiclesDD'
											});
											$("div.companyDD").append('<input type="button" value="Ok" onClick="closeThis();" />');
											$("div.vehiclesDD").append('<input type="button" value="Ok" onClick="closeThis1();" />');
									//});
								</script>
							</td>							
						</tr>
					</table>
				</td>
			</tr>
			<tr <?php if(!isset($_GET['date'])) { echo "style='display:none;'";} ?>>
				<td>				
					<div class="ListTableBox" >
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ListTable">
							<tr>								<th>S/N</th>
								<th>Comp</th>
								<th>Veh</th>
								<th>Prio</th>
								<th>Driver</th>
								<th>Job No</th>
								<th>Job Status</th>
								<th>Job Rejected Status</th>
								<th>Send Notification
								<input id="checkAll"  type="checkbox" value="1">
								</th>
								<th>
								Arrival Notification
								</th>	
								<th>CustID</th>
								<th width="500">Name</th>
								<th width="1000">Job Dest / From</th>
								<th>Job Time</th>
								<th>Flight No</th>
								<th width="1000">Remarks</th>
								<th>Country</th>							
								<th>Contact No</th>
								<th>Job Internal Remarks</th>
								<th>Driver Name</th>	
								<th width="200">Send SMS</th>	
								<th width="200">Send Driver Details</th>
							</tr>	 

							<?php 
							
							$j	=	0;
							if(!empty($result))
								{
									foreach($result as $row){
												$j++;
												
								
							?>
								<tr <?php if($j%2==0){ echo 'class="GrayBg '.$row['color_class'].'"'; }else { echo 'class="'.$row['color_class'].'"'; } ?> >									<td><?php echo $j; ?></td>
									<td <?php echo $row['td_style']; ?> ><?php echo $row['selectedDB']; ?></td>
									<td <?php echo $row['td_style']; ?> ><?php echo $row['VehNameShort']; ?></td>
									<td <?php echo $row['td_style']; ?> ><?php echo $row['CustPriority']; ?></td>
									<td <?php echo $row['td_style']; ?> ><?php echo $row['drvrDropdown']; ?>									
									<!--<div id="resultMsg<?php echo $row['selectedDB'].$row['JobNo']; ?>" style="width:100px;padding-top:2px;">&nbsp;</div>-->
									</td>
									<td <?php echo $row['td_style']; ?> >
										<a class="iframe" href="action.php?company=<?php echo $row['selectedDB']; ?>&action=JOB-DETAIL&job_id=<?php echo $row['JobNo']; ?>"><?php echo $row['JobNo']; ?></a>
									</td>
									
									<td>
									<?php
									$style = "";
									
									if(!empty($row['JobAssignedDrvID']) && $row['JobAssignedDrvID'] != $row['JobDrvID'] && $row['JobStatus']=='Accepted')
									{
										$style = "style='color:#00A214;'";
									}
									if(!empty($row['JobDrvID']) && ($row['JobStatus']=='Selected' || $row['JobStatus']=='Confirmed'))
									{
										$checkbox_class = "checkbox_class";
									}
									else
									{
										$checkbox_class = "";
									}										
									?>
									<span <?php echo $style; ?> id="<?php echo "job_".$row['JobNo']."_span"; ?>" class="span_job_status <?php echo $checkbox_class; ?>">
									<?php
									
									/* if(in_array((int)$row['JobDrvID'], $driver_static_array))
									{ */
										
										if(!empty($row['JobStatus']))
										{
											echo $row['JobStatus']; 
										}
										else
										{
											echo "--";
										}
									/* } */	
									?>
									<span>
									</td>
									<td>
									<?php
									
									if(!empty($row['JobRejectStatus']))
									{
									?>
									<a class="rejected_job_detail" href="rejected-job-detail.php?job_no=<?php echo $row['JobNo']; ?>"><?php echo $row['JobRejectStatus']; ?></a>
									<?php
									}
									else
									{
										echo "--";
									}	
									?>
									</td> 
									<td>
									
									<?php
									
									/* if(in_array((int)$row['JobDrvID'], $driver_static_array))
									{ */
										$current_date = strtotime(date('Y-m-d H:i:s'));
										$perform_date = strtotime($row['CustomJobTime']);
									
										/* if(!empty($row['JobStatus']) && $row['JobStatus'] == 'Selected' && !empty($row['JobDrvID']) && $perform_date > $current_date)
										{ */
											
										if(!empty($row['JobStatus']) && $row['JobStatus'] == 'Selected' && !empty($row['JobDrvID']))
										{
										?>
										<?php 
											/* <span class="BlkBtnLeBg <?php echo $class; ?>" id="<?php echo "send_pn_".$row['JobNo']; ?>">
											
											
											<input class="BlkBtnRiBg" type="button"  value="Send PN" name="" onclick="sendPn('<?php echo $row['selectedDB'] ?>','<?php echo $row['JobNo'] ?>','<?php echo (int)$row['JobDrvID'] ?>','<?php echo (int)$row['JobRecID'] ?>')" class="sendpn" id="<?php echo $row['selectedDB']."#".$row['JobNo'] ?>">
											
											
											</span> */
											?>
											<?php
											$checkbox_value = $row['selectedDB']."|".$row['JobNo']."|".(int)$row['JobDrvID']."|".(int)$row['JobRecID']."|".$row['JobDate'];
											
											
											
											?>
											<input type="checkbox"   class="pn_confirm" id="<?php echo "job_".$row['JobNo']; ?>" value="<?php echo $checkbox_value; ?>"  />
											
											<img src="<?php echo SITE_URL ."secure/planning/scripts/images/loading.gif";?>" class="<?php echo "job_".$row['JobNo']; ?>" width="15" height="15" style="display:none;"/>
										<?php
										}
										/* elseif(!empty($row['JobStatus']) && $row['JobStatus'] == 'Confirmed' && !empty($row['JobDrvID']) && $perform_date > $current_date )
										{ */
										elseif(!empty($row['JobStatus']) && $row['JobStatus'] == 'Confirmed' && !empty($row['JobDrvID']) )
										{		
											$checkbox_value = $row['selectedDB']."|".$row['JobNo']."|".(int)$row['JobDrvID']."|".(int)$row['JobRecID']."|".$row['JobDate'];
											
										?>
											<input type="checkbox" checked  class="pn_confirm"id="<?php echo "job_".$row['JobNo']; ?>" value="<?php   echo $checkbox_value; ?>" />
											
											<?php /* <span class="BlkBtnLeBg <?php echo $class; ?>" id="<?php echo "send_pn_".$row['JobNo']; ?>">
											
											
											<input class="BlkBtnRiBg" type="button"  value="Send PN" name="" onclick="sendPn('<?php echo $row['selectedDB'] ?>','<?php echo $row['JobNo'] ?>','<?php echo (int)$row['JobDrvID'] ?>','<?php echo (int)$row['JobRecID'] ?>')" class="sendpn" id="<?php echo $row['selectedDB']."#".$row['JobNo'] ?>">
											
											
											</span> */
											?>
											<img src="<?php echo SITE_URL ."planning/scripts/images/loading.gif";?>" class="<?php echo "job_".$row['JobNo']; ?>" width="15" height="15" style="display:none;"/>

										<?php
										}
										?>	
									<?php
									/* } */
									?>	
									<?php
								/* 	<span id="<?php echo "error_".$row['JobNo']; ?>">
											
									</span> */
									?>
									
									
									
									
									
									
									
									
									</td>
									
									<td>
									<?php
									
									if(!empty($row['JobStatus']) && $row['JobStatus'] == 'Accepted' && !empty($row['JobDrvID']) && $row['JobSrvcID'] == '001' &&  ($row['JobSendNotiArrival'] == '0' || $row['JobSendNotiArrival'] == '1') )
									{	
										$dataattr = '';
										$class = 'RedBtnLeBg';
										if($row['JobSendNotiArrival'] == '0')
										{
											$dataattr = '1';
											$class = "RedBtnLeBg";
										}
										if($row['JobSendNotiArrival'] == '1')
										{
											$dataattr = '2';
											$class = "";
										}
										
										
												
										
									?>
									<span class="BlkBtnLeBg <?php echo $class; ?>" id="<?php echo "sendpn_".$row['JobNo']; ?>">
									<input class="BlkBtnRiBg" type="button"  value="Send PN" name="" onclick="sendPnArrivalJob('<?php echo $row['selectedDB'] ?>','<?php echo $row['JobNo'] ?>','<?php echo (int)$row['JobDrvID'] ?>','<?php echo (int)$row['JobRecID'] ?>',this)" data-attr = "<?php echo $dataattr; ?>"  id="<?php echo "#".$row['JobNo'] ?>">
									</span>
									<?php
									}
									?>
									<img src="<?php echo SITE_URL ."secure/planning/scripts/images/loading.gif";?>" class="<?php echo "img_".$row['JobNo'] ?>" width="15" height="15" style="display:none;"/>
									</td>
									
									
									<td <?php echo $row['td_style']; ?> >
										<a class="iframe" href="action.php?company=<?php echo $row['selectedDB']; ?>&action=CUST-DETAIL&cust_id=<?php echo $row['CustID']; ?>"><?php echo $row['CustID']; ?></a>
									
									</td>
									<td <?php echo $row['td_style']; ?> >
										<a class="iframe" href="action.php?company=<?php echo $row['selectedDB']; ?>&action=CUST-DETAIL&cust_id=<?php echo $row['CustID']; ?>"><?php echo $row['customer_saluat_name']; ?> <?php echo $row['customer_name']; ?></a>
									</td>
									<td <?php echo $row['td_style']; ?> >
									<?php echo $row['service_name']; ?>
									</td>
									<td>									
										<a class="iframe" href="action.php?company=<?php echo $row['selectedDB']; ?>&action=JOB-DETAIL&job_id=<?php echo $row['JobNo']; ?>"><?php echo $row['job_time']; ?></a>							
									</td>	
									<td>
									<a class="iframe" href="action.php?company=<?php echo $row['selectedDB']; ?>&action=JOB-DETAIL&job_id=<?php echo $row['JobNo']; ?>"><?php echo $row['JobFlight']; ?></a>
									</td>	
									<td>
										<a class="iframe" href="action.php?company=<?php echo $row['selectedDB']; ?>&action=JOB-DETAIL&job_id=<?php echo $row['JobNo']; ?>"><?php echo $row['JobRem']; ?></a>
									</td>
									<td>
										<?php 
											echo $row['country'];
										?>									
									</td>									
									<td>
										<?php 
											echo $row['contact_no'];
										?>									
									</td>
									<td>
									<?php if($row['JobIntRemark'])
									{?>
									<a id='<?php echo 'intrem'.$row['JobNo']; ?>' href="javascript:void(0);" rel='<?php echo $row['selectedDB'].'~'.$row['JobNo'];?>' class="btnShowSimple">EDIT</a>	
									<?php } else { ?>
										<a id='<?php echo 'intrem'.$row['JobNo']; ?>' href="javascript:void(0);" rel='<?php echo $row['selectedDB'].'~'.$row['JobNo'];?>' class="btnShowSimple">NEW</a>							
										<?php } ?>
									</td>
									
									<td>
									
									<?php //echo $row['DrvName']; ?>
									<?php
									if(in_array((int)$row['JobDrvID'], $driver_static_array))
									{
									?>
									<a class="driver_detail" href="driver-detail.php?driver_id=<?php echo $row['JobDrvID']; ?>"><?php echo $row['DrvName']; ?></a>
									<?php
									}
									else
									{
										echo $row['DrvName'];
									}	
									?>
									</td>
									<td>
										<?php 
										$updateSMSstr	=	$row['selectedDB']."|".$row['JobNo'];
										$updateSMSchkd	=	'';
										if(isset($_SESSION['smsUpdateArr'][$updateSMSstr])){
											$updateSMSchkd	=	" checked='checked '";
										}
										if(!empty($row['DrvName'])){
											if((empty($row['JobDrvSendSMS']) or $row['JobDrvSendSMS'] == '0000-00-00 00:00:00') && !empty($row['DrvName'])) { ?>
											<input class="updateSMS" rel="<?php echo $row['selectedDB']; ?>" type='checkbox' name="" id="<?php echo $updateSMSstr; ?>" <?php echo $updateSMSchkd; ?> value="<?php echo $updateSMSstr; ?>"/>
											<?php }else{
												echo "Sent ".date("d-M-y",strtotime($row['JobDrvSendSMS']))."<br/>@".date("H:i:s",strtotime($row['JobDrvSendSMS']));
											} 
										}
										?>
									</td>
									<td>
									<?php 
									
									if($row['JobSMSRemindSent']=='' || $row['JobSMSRemindSent']==null) { $showcheck = true; }elseif($row['JobSMSRemindSent']!='' && $row['JobCustSendSMS']!='0000-00-00 00:00:00'){ $showcheck = true; }else{ $showcheck = false;}
									if($row['JobDrvID'] == '')
									{ 
										$showcheck = false;
									}
									if(($row['selectedDB'] == 'HSBC') && $showcheck == true) 
									{ //echo date('Y-m-d H:i:s');
									$updateCustSMSchkd	=	'';
									if(isset($_SESSION['smsUpdateCustArr'][$updateSMSstr])){
											$updateCustSMSchkd	=	" checked='checked '";
										}
									?>
									<input class="SendCustSMS" rel="<?php echo $row['selectedDB']; ?>" type='checkbox' name="" id="<?php echo $updateSMSstr; ?>" <?php echo $updateCustSMSchkd; ?> value="<?php echo $updateSMSstr; ?>"/>
									<?php
									}elseif($row['JobSMSRemindSent']){
									echo "Sent ".date("d-M-y",strtotime($row['JobSMSRemindSent']))."<br/>@".date("H:i:s",strtotime($row['JobSMSRemindSent']));
									}
									?>
									
									<?php
									//code to show checkbox for OCBC
									/* echo '<pre>'; print_r($row); die; */
									if($row['JobBillID'] == 15458 && ($row['selectedDB'] == 'WLR') && $row['JobDrvID'] != '')
									{
									$jobDateTime =  $row["JobDate"]." ".substr($row["JobTime"],0,2).":".substr($row["JobTime"],2).":00"; 
									$jobtime =  strtotime($jobDateTime ); 
									/* echo $jobtime.'<br/>';
									echo  mktime(); die; */
									if($jobtime > mktime())
									{ 
									$updateCustSMSchkd	=	'';
									if(isset($_SESSION['smsUpdateCustArr'][$updateSMSstr])){
											$updateCustSMSchkd	=	" checked='checked '";
										}
									
									?>
									<input class="SendCustSMS" rel="<?php echo $row['selectedDB']; ?>" type='checkbox' name="" id="<?php echo $updateSMSstr; ?>" <?php echo $updateCustSMSchkd; ?> value="<?php echo $updateSMSstr; ?>"/>
									<?php
									}
									
									}
									
									
									?>
									
									
									</td>
								</tr>	  
							<?php
									}
								}
								
							?>
						</table>
					</div>				
				</td>
			</tr>
		</table>
	</div>	
		<?php
	
	if(isset($_SESSION['Auth']['Customer']['CustTypeID']) && ($_SESSION['Auth']['Customer']['CustTypeID']=='001' )){	
	
	?>
	
	<div class="chat-boxes">
	 


	</div>


	<div class="chat-wrp">
	  <div class="chat-title Bluedarkbg">Driver List<a href="javascript:;" id="chat_window_icon" class="minicon-rw"><img src="scripts/images/min-icon.jpg" /></a></div>
	  <div class="chat-mid-wrp">
		<div class="chat-mid-list">
		  <?php	while ($rowDriverData = mysql_fetch_array($resultDriver,MYSQL_ASSOC)) {
		 ?>
		  <div id="<?php echo "driver_".(int)$rowDriverData['DrvID']?>" class="chat-mid-list-rw">
			<div class="chat-mid-li-img">
			  <?php
				if(!empty($rowDriverData['DrvProfilePic']) && file_exists(DRIVER_THUMB_IMAGE_DIR_50x50.$rowDriverData['DrvProfilePic']))
				{
				
					$img_url = SITE_URL ."planning/". DRIVER_THUMB_IMAGE_DIR_50x50.$rowDriverData['DrvProfilePic'];
				?>
			  <img alt="<?php echo $rowDriverData['DrvName']; ?>"  title="<?php echo $rowDriverData['DrvName']; ?>" src = "<?php echo $img_url; ?>"/>
			  <?php
				}
				else
				{
					$img_url = SITE_URL ."planning/". DRIVER_IMAGE_DIR ."no_image.jpg";
				?>
			  <img alt="<?php echo $rowDriverData['DrvName']; ?>"  title="<?php echo $rowDriverData['DrvName']; ?>" src = "<?php echo $img_url; ?>"/>
			  <?php	
				}
			
			?>
			</div>
			<div class="chat-mid-li-txt">
			  <div class="chatwindow" data-driver="<?php echo (int)$rowDriverData['DrvID'];?>"><?php echo $rowDriverData['DrvName'];?></div>
			  <?php if ($rowDriverData['DrvDeviceToken']!='') { ?>
			  <span class="online-st green"></span>
			  <?php } else { ?>
			  <span class="online-st yellow"></span>
			  <?php }?>
			</div>
		  </div>
		  <?php } ?>
		</div>
		<div class="srh-box-rw">
		  <input name="" type="text" id="searchdriver" class="srh-icon-fild"  placeholder="Search" />
		  <input name="" type="button" class="srh-icon-chat" />
		</div>
	  </div>
	</div>
	
	
	
	
	<?php
	}
	?>
	<script type="text/javascript">
	var allowpopu = "";
	var chatinitiate = "";
	<!-- start chat script ---------->
	$(document).ready(function(){
		
		var name = '<?php echo $userName; ?>',
		email = "",
		image = "<?php echo $image; ?>",
		friend = "",
		
		staffId = '<?php echo $staffId;?>',
		type= '<?php echo $type ?>';
		
		
		var socket = io.connect("https://wolero.com:4000");
		 socket.emit('join_room', { id: '<?php echo $_SESSION['Auth']['Customer']['CustID'];?>'});
	
	socket.on("broadcastRequest", function(data){
			 
			$("#driver_"+data.driverId).addClass('red-bg-list');
			$('#driver_'+data.driverId).prependTo('.chat-mid-list');
			$(".chat-title").addClass('GreyBluedarkbg');
			$(".chat-title").removeClass('Bluedarkbg');
		  
	   $("[data-driver='" + data.driverId + "']").attr("data-threadid",data.threadId);
			 
		
		
	});
	
	socket.on("driver_disconnected",function(data){
		if(data != ""){
		//$('.chat_data_list[data-threadid="'+data+'"]').find('textarea').remove();
		//$('.chat_data_list[data-threadid="'+data+'"]').find('.send-btn').remove();
		// $('header.chat_data_list[data-threadid="'+data+'"]').append('<div style="color:black;float:left;width:100%;"><b>Driver left chat</b></div>');
		//$('.chat_data_list[data-threadid="'+data+'"]').find('ol.chat-box-wolero').append('<li><b>Driver Leave the chat</b></li>');
		$('.chat_data_list[data-threadid="'+data+'"] > ol').append('<li style="color:red;font-size:15px;">Driver Left Chat</li>');
		
		}
		scrollToDivBottom()	
	 	
	});
	
	
	
	$("#chat_window_icon").on('click',function(){
		$(".chat-mid-wrp").toggle();
		//$(".chat-title").removeClass('GreyBluedarkbg');
		//$(".chat-title").addClass('Bluedarkbg');
		
	})
	 
	
	
	$("#searchdriver").keyup(function(){
		
		if($("#searchdriver").val().length >= 3 || $("#searchdriver").val().length == 0)
		{
			
			socket.emit('driver_searching', { searchtxt: $("#searchdriver").val(),popup_type:'1'});
		}
		
	})
	
	// code is used to auto refresh the driver list 
    setInterval(function() { 
	   
	   if($('.chat-mid-list').find('.red-bg-list').length==0){
			socket.emit('driver_searching', {popup_type:'2'}) 
	    }
	   }, 300000);
	
	
	socket.on("driver_searching_result", function(data){
		// console.log(data);
		 
		var html_data = '';
		$.each(data.search_result, function(index, element) {
		   //console.log(element);
			 
			html_data = html_data + '<div><div id="driver_'+ parseInt(element.DrvID)+'" class="chat-mid-list-rw"><div class="chat-mid-li-img">';
			if(element.DrvProfilePic === null)
			{
				img = '<?php echo SITE_URL ."planning/". DRIVER_IMAGE_DIR ."no_image.jpg";?>';
			}
			else
			{
				img = 
				'<?php echo SITE_URL ."planning/". DRIVER_THUMB_IMAGE_DIR_50x50;?>'+element.DrvProfilePic;
			}
			
			html_data = html_data + '<img alt="'+element.DrvName+'" title="'+element.DrvName+'" src="'+img+'"/>';
			html_data = html_data + '</div><div class="chat-mid-li-txt"><div class="chatwindow" data-driver="'+ parseInt(element.DrvID)+'" data-threadid="">'+element.DrvName+'</div>';
			
			
			if (element.DrvDeviceToken== null) {
				html_data = html_data + '<span class="online-st yellow"></span>';	 
			} else { 
				html_data = html_data + '<span class="online-st green"></span>';
			}
			html_data = html_data + '</div></div></div>';
			
		});
		
		if (data.refresh==1) {
			$(".chat-title").removeClass('GreyBluedarkbg');
			$(".chat-title").addClass('Bluedarkbg');
		}
		
		$(".chat-mid-list").html(html_data);
		
		
	});
	socket.on("refresh_drivers_list",function(data){
	
		socket.emit('driver_searching', {popup_type:'3'});
	});
	
	socket.on("make_popup_new",function(data){
				var threadId =  data.threadId;
				  if (data.threadId !='') {
					 
					socket.emit('join_room', {id: threadId, image: image,username: name});
				}  
				 messageTimeSent = $(".timesent");
			    var rightDimention = parseInt($('.chat-container').length)+1;
				var rightWid = 310*rightDimention;
				html_data = '';
				html_data+='<section class="chat-container" style="right:'+rightWid+'px">';
				html_data+='<header class="top-header chat_data_list" data-threadId='+threadId+'>';
				html_data+='<div class="left">';
				
				if (data.driver_detail[0].DrvDeviceToken==null) {
					html_data+='<span class="chat-active yellow"></span>';
				} else {
					html_data+='<span class="chat-active green"></span>';
				}
				html_data+='<span class="top-header-tit">'+data.driver_detail[0].DrvName +'</span></div>';
				html_data+='<div class="right">';
				html_data+='<span class="icon typicons-minus" onclick="showHidePopup('+parseInt(data.driver_detail[0].DrvID)+');" data-driver="'+parseInt(data.driver_detail[0].DrvID)+'"><img src="scripts/images/min.png"/></span>';
				
				html_data+='<span class="close_popup icon typicons-times" data-driver="'+parseInt(data.driver_detail[0].DrvID)+'" data-threadid="'+threadId+'"><img src="scripts/images/close.png"/></span>      </div>';
				html_data+="</header>";
				chat_list_popup = "chat_list_popup_"+parseInt(data.driver_detail[0].DrvID);
				

		  html_data+="<div id="+chat_list_popup+" data-threadId="+threadId+" class='chat_data_list'><ol class='chat-box-wolero' >";
			
				$.each(data.chat_request, function(index, element) {
				  
				
				  
				if(element.DrvProfilePic === null)
				{
					img = '<?php echo SITE_URL ."planning/". DRIVER_IMAGE_DIR ."no_image.jpg";?>';
				}
				 
				else
				{
					img = 
					'<?php echo SITE_URL ."planning/". DRIVER_THUMB_IMAGE_DIR_50x50;?>'+element.DrvProfilePic;
				}   
				
				  var stafffID = '<?php echo $_SESSION['Auth']['Customer']['CustID'];?>';
				 //SenderId
				 
				 if (element.SenderId == stafffID ) {
                        who = 'me';
						//img = '<?php echo SITE_URL ."planning/". DRIVER_IMAGE_DIR ."no_image.jpg";?>';
                    }
					
                    else {
                        who = 'another';
						
						
                    }
					
						
				 
				  html_data+="<li class=" + who + ">";
				  html_data+="<div class='avatar-icon'>";
				  html_data+="<img src="+img+"></div>";
				  html_data+="<div class='messages'>";
				  html_data+="<p>"+element.message+"</p>";
				  html_data+="<i class='timesent' data-time=" +element.created+ "></i>";
				  html_data+="</div>";
				  html_data+="</li>";
			  	
				});
				
				
		  
		html_data+="</ol>";
		var Drvattrtexarea ="textarea_"+parseInt(data.driver_detail[0].DrvID);
		
		html_data+="<textarea rows='4' id="+Drvattrtexarea+" cols='40'  resize='none' class='textarea-box'>";
	 	html_data+="</textarea>";
		html_data+="<input type='hidden' id='stafffid' value='<?php echo $_SESSION['Auth']['Customer']['CustID'];?>' name='stafffid'>";
		html_data+="<a href='javascript:;' data-driver="+parseInt(data.driver_detail[0].DrvID)+" data-threadid="+threadId+" data-img ="+image+" class='send-btn'>SEND</a></div>";
		html_data+="</section>";  
		
		 /* setInterval(function() {

                    messageTimeSent.each(function() {
                        var each = moment($(this).data('time'));
                        $(this).text(each.fromNow());
						 
                    });

                }, 10); */
		
			if ($('#chat_list_popup_'+parseInt(data.driver_detail[0].DrvID)).length === 0){
				$('.chat-boxes').append(html_data);
				//scrollToDivBottom()	
			 }
			
					
			setInterval(function() {

              $(".timesent").each(function() {
                        var each = moment($(this).data('time'));
                        $(this).text(each.fromNow());
						 
                    });

                }, 500);
	      scrollToDivBottom()	
	}); 
	
	$(document).on('click','.chatwindow',function(event){
		var threadId = '';
		
		var driverId = $(this).attr('data-driver');
		var a = 'CheckClick';
	    var CheckClick11 = a+'_'+driverId;
	    var CheckClick11 =0;
		var b = 'CheckClick_pn';
	    var CheckClick11_pn = b+'_'+driverId;
	    var CheckClick11_pn =0;
		
		var  sendpn = true;
		$('#driver_'+driverId).closest('.red-bg-list').removeClass('red-bg-list');
		 if ($(this).attr('data-threadid') && $(this).attr('data-threadid')!='') {
			var threadId  =$(this).attr('data-threadid');
			sendpn = false;
		  } 
		 socket.emit('check_driver_in_active_table', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',driverId:driverId,threadId:threadId}); 
		  
		socket.on("active_driver_detail",function(data){
			if(CheckClick11==0){
				allowpopu = "allow";
				if (data.result) {
				if (data.result[0].senderType=='staff') { 
					if (data.result[0].SenderId==staffId){
							var driver= data.result[0].RecId;
							var staff= data.result[0].SenderId;
							 allowpopu = "allow";
							 
					} else if(data.result[0].RecId==staffId){
							 allowpopu = "allow";
							var driver= data.result[0].SenderId;
							var staff= data.result[0].RecId; 
					} else { 
					 if(CheckClick11==0){
						 allowpopu = "not allowed popu";
						// apply new code to open popup 
						 alert("Driver is chating "+ data.result[0].name +". Please wait......");  
						// apply new code to open popup end here 
						
						 }
					}	
					
				} else if (data.result[0].senderType='driver'){
				
				}
			} else {
				  
					if(CheckClick11==0){
						var threadId  =data.threadId;
						if (threadId) {
							socket.emit('initiate_chat', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',threadId:threadId,driverId:driverId,senderType:"staff",staffname:name});  
						}
					}
				 }	

			}
	
		
			 if(CheckClick11==0){ 
				CheckClick11 = parseInt(CheckClick11)+1;
				if ($('#chat_list_popup_'+parseInt(driverId)).length === 0){
					
					$(".chat-title").removeClass('GreyBluedarkbg');
					$(".chat-title").addClass('Bluedarkbg');
					
							if ($(this).attr('data-threadid') && $(this).attr('data-threadid')!='') {
								
								var threadId  = $(this).attr('data-threadid');
								if (allowpopu=='allow'){
								socket.emit('fetch_chat_request_new', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',threadId:threadId,driverId:driverId}); 
								}
								
							} else if (data.threadId && data.threadId!='') {
								threadId  = data.threadId
								if (allowpopu=='allow'){
								socket.emit('fetch_chat_request_new', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',threadId:threadId,driverId:driverId}); 
								}
							
							}else{
							
							socket.emit('check_threadID', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',driverId:driverId});
							
							socket.on('send_threadID',function(data){
								
								if (data.ThreadID!=''){
									threadid = data.ThreadID;
									
									// add thread id to driver list popup to initiate chat with driver
									if ($('#chat_list_popup_'+parseInt(data.driverId)).length === 0){
										$("[data-driver='" + data.driverId + "']").attr("data-threadid",threadid);
											if (allowpopu=='allow'){
												socket.emit('fetch_chat_request_new', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',threadId:threadid,driverId:data.driverId});
											}	
									}
									
									
									} else {
										threadid = getRandomInt(5,5000000000);
									// add thread id to driver list popup to initiate chat with driver
										if ($('#chat_list_popup_'+parseInt(data.driverId)).length === 0){
											$("[data-driver='" + data.driverId + "']").attr("data-threadid",threadid);
												if (allowpopu=='allow'){	
												 socket.emit('fetch_chat_request_new', { staffId:'<?php echo $_SESSION['Auth']['Customer']['CustID'];?>',threadId:threadid,driverId:data.driverId});
												}	
					
										}	
									
									}
								
								// send notification to driver to initiate chat with staff	
								 
								if(CheckClick11_pn==0){
									CheckClick11_pn = parseInt(CheckClick11_pn)+1;
									
									if (allowpopu=='allow' && sendpn){
										var loggedinID ='<?php echo $_SESSION['Auth']['Customer']['CustID'];?>';
								
									   $.ajax({
										url:"send-notification-chat.php",
										type:"POST",
										async:false,
										data:{threadID:threadid,driverId:data.driverId,staffId:loggedinID},
										success: function(response){
												//alert(response);
												
											}
										});
										
									}

								}			
								
							});
						} 
					
					
				}
			
			
			 }
		
		});
	
	
	});  
	 
	$(document).on('click','.send-btn',function(){
		message = $("#textarea_"+$(this).attr("data-driver")).val();
		threadId = $(this).attr("data-threadid");
		img = $(this).attr("data-img");
		driverid = $(this).attr("data-driver");
		if (threadId !='') {
					var threadId =  threadId;
					socket.emit('join_room', {id: threadId, image: img,username: name});
				} 
		var loggedinID ='<?php echo $_SESSION['Auth']['Customer']['CustID'];?>';
		socket.emit('msg', {msg: message, SenderId: '<?php echo $_SESSION['Auth']['Customer']['CustID']; ?>', user: name, img: img, type: type, threadId: threadId,driverId:driverid,staffid:loggedinID});
		 
		 $("#textarea_"+$(this).attr("data-driver")).val("");
    });
	
	
	     socket.on('receive_new', function(data1) {
				 //console.log('receive_new staff');
				 //console.log(data1);
                    
                    if (data1.msg.trim().length) {
                        createChatMessage(data1.msg, data1.user, data1.img,data1.senderid,data1.driverid,moment());
                        scrollToDivBottom()
                    }
                }); 
	
	
			socket.on('receive', function(data) {
			//console.log('receive testhans');
			//console.log(data);
			if ($('#chat_list_popup_'+data.driverid).length === 0){
					 
						$("#driver_"+data.driverid).addClass('red-bg-list');
						$('#driver_'+data.driverId).prependTo('.chat-mid-list');
				}
			   if (data.msg.trim().length) {
					createChatMessage(data.msg, data.user, data.img,'',data.driverid,moment());
					scrollToDivBottom();
				}
			});
	
		
		$(document).on('click','.close_popup',function(){
			driverId= $(this).attr('data-driver');
			threadId= $(this).attr('data-threadid');
			
			if (driverId!='' && threadId!='') {
				
				socket.emit('remove_joined_driver', {id: threadId, driverId: driverId,img:image,username:name});
				
			}
			
			$("[data-driver='" + driverId + "']").attr("data-threadid",""); 
			
			$("#driver_"+driverId).removeClass('red-bg-list');
			
			$('#chat_list_popup_'+driverId).closest('.chat-container').remove();
			$( ".chat-container" ).each(function(key) {
				var RightDimension = 310*(key+1)
				$( this ).css( "right",RightDimension );
			});
			
			// socket.disconnect(threadId);
			
		});
});


		



	function createChatMessage(msg, user, img,sender,driver,now) {
				 
					var staffId = '<?php  echo $_SESSION['Auth']['Customer']['CustID'];?>';
                    var who = '';

                    if (sender == staffId) {
                        who = 'me';
                    }
                    else {
                        who = 'another';
                    }
					
				 
					
					
                    var li = $(
                            '<li class=' + who + '>' +
                            '<div class="avatar-icon">' +
                            '<img src=' + img + ' /></div>' +
							'<div class="messages">'+
							'<p>'+msg+'<p>' +
                            '<i class="timesent" data-time=' + now + '></i>' +
                            '</div>' +
                            '</li>');

						
                    // use the 'text' method to escape malicious user input
                    //li.find('p').text(msg);
                   // li.find('b').text(user);
				   
				   $('#chat_list_popup_'+driver).children(".chat-box-wolero").append(li);				 
					//$(".chat-box-wolero").append(li);
                     messageTimeSent = $(".timesent");
                     messageTimeSent.last().text(now.fromNow());
                }	



 function showHidePopup(driverId)
	{
		
		
		//alert($("#chat_list_popup_"+driverId).css('display')); 
		  if($("#chat_list_popup_"+driverId).css('display') == 'none')
		{	
			
			$("#chat_list_popup_"+driverId).css('display','block');
		}
		else if($("#chat_list_popup_"+driverId).css('display') == 'block')
		{
			$("#chat_list_popup_"+driverId).css('display','none');
		}  
	}

	
	 function ClosePopup(driverId)
	{
		$("[data-driver='" + driverId + "']").attr("data-threadid",""); 
		$('#chat_list_popup_'+driverId).closest('.chat-container').remove();
		$( ".chat-container" ).each(function(key) {
			var RightDimension = 310*(key+1)
			$( this ).css( "right",RightDimension );
		});
		//alert($("#chat_list_popup_"+driverId).css('display')); 
		/*  if($("#chat_list_popup_"+driverId).css('display') == 'none')
		{	
			
			$("#chat_list_popup_"+driverId).css('display','block');
		}
		else if($("#chat_list_popup_"+driverId).css('display') == 'block')
		{
			$("#chat_list_popup_"+driverId).css('display','none');
		}  */ 
	}
	

function scrollToBottom() {
                    $("html, body").animate({scrollTop: $(document).height() - $(window).height()}, 1000);
                }
				
	function scrollToDivBottom() {
                    $(".chat-box-wolero").animate({scrollTop:  1000000 }, 1000);	
                }			

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}				
  
function windowOpen(url) {
	if(checkWin())
	{
	w = '900';
	h = '900';;
	var left = (screen.width / 2) - (w / 2);
	var top = (screen.height / 2) - (h / 2);;
	mywindow = window.open(url, "chatdetailwindow", 'addressbar=no, location=no, resizable=no, menubar=no, toolbar=no, location=no, scrollbars=yes,width=' + w + ', height=' + h + ', top=' + top + ', left=' + left + '');
	 
	}
	else
	{
		
		alert("Please closed open chat window then open another window for chat. ")
		
	}

}

function checkWin() {
    if (!mywindow) {
        return true;
    } else {
        if (mywindow.closed) { 
            return true;
        } else {
			
            return false;
        }
    }	
}
	
	<!-- end chat script ---------->
	function showAll(){
		<?php
			if(isset($_GET['date'])){
			$strdate	=	'?&date='.$_GET['date'];
			}else{
			$strdate	=	'';			
			}
		?>
		window.location	=	'<?php echo $indexFileName.$strdate; ?>';
	}
	function toAction(url, action){
		if(action=='all'){
			window.location	=	'<?php echo $indexFileName; ?>';
		}else{
			url				=	url + '&' + action;
			window.location	=	url;
		}
	}
	function OpenInNewTab(url,action) {
		url				=	url + '&' + action;
		var win = window.open(url, '_blank');
		win.focus();
	}	
	function closeThis(){		
		$("div.companyDD div:eq(0) > ul > li:last-child > a").click();		
	}
	function closeThis1(){		
		$("div.vehiclesDD div:eq(0) > ul > li:last-child > a").click();
	}	
	function setDriver(company,job_id,driver_id){
		
		//var elem_id		=	'#resultMsg' + company + job_id;
		//alert(company + " " + job_id + " " + driver_id);
		$.blockUI(); 
		$('#Drvr'+ company + job_id).attr('style','color:#CC0033;border:1px solid #CC0033;');
		//$(elem_id).html('');
		//$(elem_id).html('saving driver..');
		var data_save		=	new Array();
		data_save.push(
			{ name:"action", value:'ASSIGN-DRIVER' },
			{ name:"company", value:company },
			{ name:"job_id", value:job_id },
			{ name:"driver_id", value:driver_id }	
		);
		$.get("action.php",data_save, function(data){
			//console.log(data);
			try{
				data = $.parseJSON(data);
				if(data.key=='error'){
					alert("An error occured, please try again later.");
				}
				else if(data.notchange== '1')
				{
					alert("Job time has already been passed for which you are assigning driver.");
					location.reload(true);
					
				}	
				else if(data.key=='success'){
					//$(elem_id).html('driver saved..');
					$('#Drvr'+ company + job_id).attr('style','color:#000;border:1px solid #33CC33;');	
					location.reload(true);
				}				
			}catch(e){
				//console.log(e.toLocaleString());
				//$(elem_id).html('driver could not saved, please try again..');
				alert("An error occured, please try again later.");
			}
			$.unblockUI();
		});
	}	
	$(document).ready(function(){					
		$(".iframe").fancybox({
			'width'				: '75%',
			'height'			: '75%',
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe' 
		});
		$(".driver_detail").fancybox({
			'width'				: '75%',
			'height'			: '75%',
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe' 
		});
		$(".rejected_job_detail").fancybox({
			'width'				: '75%',
			'height'			: '75%',
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe' 
		});
		var DateTextBox = $('#search');
		DateTextBox.datepicker({		
			hideIfNoPrevNext: true,
			minDate: -7,
			maxDate: 30,
			'dateFormat': "yy-mm-dd",showAnim: 'fold',changeYear: true, changeMonth: true,
			 onSelect: function(dateText) {
				//toAction('<?php echo getTargetUrl('date');?>','date='+ dateText);
			}
		});
		
		$('.updateSMS').change(function () {
			var data_save		=	new Array();
			data_save.push(
				{ name:"action", value:'SMS-UPDATE' },
				{ name:"data", value:$(this).attr('id') },
				{ name:"company", value:$(this).attr('rel') }	
			);
			var elem_id	 =	this;
			var reverseFlag	=	'';
			$.blockUI(); 
            if($(this).is(':checked')){
				data_save.push(
					{ name:"type", value:'1' }
				);
				reverseFlag		=	0;
			}else{
				data_save.push(
					{ name:"type", value:'0' }
				);
				reverseFlag		=	1;
			}			
            $.get("action.php",data_save, function(data){
				//console.log(data);
				try{
					data = $.parseJSON(data);
					if(data.key=='error'){	
						alert("An error occured, please try again later.");
						if(reverseFlag == 1){
							$(elem_id).prop("checked",true);
						}else{
							$(elem_id).prop("checked",false);
						}						
					}else if(data.key=='success'){
						
					}				
				}catch(e){
					alert("An error occured, please try again later.");	
					if(reverseFlag == 1){
						$(elem_id).prop("checked",true);
					}else{
						$(elem_id).prop("checked",false);
					}
				}
				$.unblockUI();
			});
		});	
		$('.SendCustSMS').change(function () {
			var data_save		=	new Array();
			data_save.push(
				{ name:"action", value:'SMS-CUST-UPDATE' },
				{ name:"data", value:$(this).attr('id') },
				{ name:"company", value:$(this).attr('rel') }	
			);
			var elem_id	 =	this;
			var reverseFlag	=	'';
			$.blockUI(); 
            if($(this).is(':checked')){
				data_save.push(
					{ name:"type", value:'1' }
				);
				reverseFlag		=	0;
			}else{
				data_save.push(
					{ name:"type", value:'0' }
				);
				reverseFlag		=	1;
			}			
            $.get("action.php",data_save, function(data){
				//console.log(data);
				try{
					data = $.parseJSON(data);
					if(data.key=='error'){	
						alert("An error occured, please try again later.");
						if(reverseFlag == 1){
							$(elem_id).prop("checked",true);
						}else{
							$(elem_id).prop("checked",false);
						}						
					}else if(data.key=='success'){
						
					}				
				}catch(e){
					alert("An error occured, please try again later.");	
					if(reverseFlag == 1){
						$(elem_id).prop("checked",true);
					}else{
						$(elem_id).prop("checked",false);
					}
				}
				$.unblockUI();
			});
		});
		
		
		$('.pn_confirm').on('click',function(){
			var __this = $(this);
			$("."+__this.attr('id')).css('display','block');
			if($(this).is(':checked'))
			{
				checkstatusval = '1'
			}
			else
			{
				checkstatusval = '0'
			}
			var checkvalue = $(this).val();
			$.ajax({
			url:"sendNotificationAssignJob.php",
			type:"POST",
			async:false,
			data:{checkval:checkvalue,checkstatus:checkstatusval},
				success: function(response){
					//alert(checkstatusval);
					if(response == '1')
					{
						$("#"+__this.attr('id')+'_span').html('Confirmed');
					}
					else if(response == '0')
					{
						$("#"+__this.attr('id')+'_span').html('Selected');
					}
					$("."+__this.attr('id')).css('display','none');
				}
			});
		});
		
		
		$('#checkAll').on('click',function(){
			var __this = $(this);
			if($(".pn_confirm").length == '0')
			{
				alert("There is no checkbox available for select.");
				return false;
			}	
			
				if($(this).is(':checked'))
				{
					
					checkstatusval = '1';
					
					$(".pn_confirm").attr('checked',true);
					$('.checkbox_class').text('Confirmed');
					//check_boxes	=	$('.pn_confirm:not(:checked)').map(function() {return this.value ;}).get().join(',');
					
				}
				else
				{
					
					$(".pn_confirm").attr('checked',false);
					$('.checkbox_class').text('Selected');
					//check_boxes	=	$('.pn_confirm:checked').map(function() {return this.value ;}).get().join(',');
					checkstatusval = '0';
				}				
				
				
				check_boxes	=	$('.pn_confirm').map(function() {return this.value ;}).get().join(',');
				//alert(check_boxes);
			
				var data_save		=	new Array();
				data_save.push(
					{ name:"checkstatusval", value:checkstatusval },
					{ name:"data", value:check_boxes}
				);
				$.blockUI();
				
				$.post("multple-confirm-job.php",data_save, function(data){
					//console.log(data);
					try{
						data = $.parseJSON(data);
						if(data.key=='error'){	
							alert("An error occured, please try again later.");
													
						}else if(data.key=='success'){
							
							
							//alert("Jobs confirmed successfully.");	
							//location.reload(true); 
						}				
					}catch(e){
						alert("An error occured, please try again later.");						
					}
					$.unblockUI();
				});
				
			
		})
		
		
	});
	
	function searchData(){
		datepickerVal		=	$('#search').val();
		if(datepickerVal == ''){
			alert("Please select the date.");
			return false;
		}
		toAction('<?php echo getTargetUrl('date');?>','date='+ datepickerVal);	
	}
	function sendAllSMS(){
		var all_checked		=	'';
		all_checked	=	$('.updateSMS:checked').map(function() {return this.value ;}).get().join(',');
		if(all_checked == ''){
			alert("Please select at lease one driver to send SMS");
			return false;
		}
		var data_save		=	new Array();
			data_save.push(
				{ name:"action", value:'SEND-SMS' },
				{ name:"data", value:all_checked}	
			);
			var elem_id	 =	this;
			var reverseFlag	=	'';
			$.blockUI();             			
            $.post("action.php",data_save, function(data){
				//console.log(data);
				try{
					data = $.parseJSON(data);
					if(data.key=='error'){	
						alert("An error occured, please try again later.");
												
					}else if(data.key=='success'){
						alert("All Message has been sent successfully.");	
						location.reload(true); 
					}				
				}catch(e){
					alert("An error occured, please try again later.");						
				}
				$.unblockUI();
			});
	}
	function sendAllDriverSMS(){
		var all_checked		=	'';
		all_checked	=	$('.SendCustSMS:checked').map(function() {return this.value ;}).get().join(',');
		if(all_checked == ''){
			alert("Please select at lease one cutomer to send SMS");
			return false;
		}
		var data_save		=	new Array();
			data_save.push(
				{ name:"action", value:'SEND-DRIVER-SMS' },
				{ name:"data", value:all_checked}	
			);
			var elem_id	 =	this;
			var reverseFlag	=	'';
			$.blockUI();             			
            $.post("action.php",data_save, function(data){
				//console.log(data);
				try{
					data = $.parseJSON(data);
					if(data.key=='error'){	
						alert("An error occured, please try again later.");
												
					}else if(data.key=='success'){
						alert("All Message has been sent successfully.");	
						location.reload(true); 
					}				
				}catch(e){
					alert("An error occured, please try again later.");						
				}
				$.unblockUI();
			});
	}
	
	
	

	
	
	function sendNotificationAllDriver(){
		if($(".datepicker").val() != '')
		{
			$.blockUI(); 
			$.ajax({
				url: "sendNotificationAllDrivers.php",
				type:"POST",
				data:{'date_str':$(".datepicker").val()},
				success: function(data){
				try{
					data = $.parseJSON(data);
					if(data.key=='error'){	
						alert("An error occured, please try again later.");
												
					}else if(data.key=='success'){
						alert("All Notification has been sent successfully.");	
						location.reload(true); 
					}				
				}catch(e){
					alert("An error occured, please try again later.");						
				}
				$.unblockUI();
					
			}});
		}
		else
		{
			alert('Invalid action.Please select date.');
		}
	}
	
	
	
	
	
	$(document).ready(function ()
        {
            $(".btnShowSimple").click(function (e)
            {
			var Arr = $(this).attr('rel').split('~');
				$('#remjobdb').val(Arr[0]);
				$('#remjobid').val(Arr[1]);
				$('#remint').val('');
				if($(this).text() != 'NEW')
				{
				$.blockUI();	
				$.ajax({
				method: "POST",
				url: "action.php",
				data: { db: Arr[0], jobid: Arr[1] , action: "FETCH-INT-REMARK" },
				success : function(data) {
				data = $.parseJSON(data);
				if(data.key=='error'){	
						alert("An error occured, please try again later.");
												
					}else if(data.key=='success'){
						$('#remint').val(data.remark);
						ShowDialog(false);
                e.preventDefault();
					}				
				$.unblockUI();	
				}
				});
				
				
				}else{
				ShowDialog(false);
                e.preventDefault();
				}
            });

            

            $("#btnClose").click(function (e)
            {
                HideDialog();
                e.preventDefault();
            });

            $("#btnSubmit").click(function (e)
            {
			
			
				
				brand = $('#remsaveform').serialize();
				var data_save		=	new Array();
				data_save.push(
				{ name:"action", value:'UPDATE-INT-REM' },
				{ name:"data", value:brand}	
			);
			
			$.blockUI();             			
            $.post("action.php",data_save, function(data){
				//console.log(data);
				try{
					data = $.parseJSON(data);
					if(data.key=='error'){	
						alert("An error occured, please try again later.");
												
					}else if(data.key=='success'){
						alert("Remark has been saved.");	
						rowid = data.id;
						$('#'+rowid).text(data.msg);
						//location.reload(true); 
					}				
				}catch(e){
					alert("An error occured, please try again later.");						
				}
				$.unblockUI();
				 HideDialog();
                e.preventDefault();
			});
			
			
               
            });
        });

        function ShowDialog(modal)
        {
            $("#overlay").show();
            $("#dialog").fadeIn(300);

            if (modal)
            {
                $("#overlay").unbind("click");
            }
            else
            {
                $("#overlay").click(function (e)
                {
                    HideDialog();
                });
            }
        }

        function HideDialog()
        {
            $("#overlay").hide();
            $("#dialog").fadeOut(300);
        } 
		
		
		function sendPnArrivalJob(company,jobNo,driverId,jobRecID,obj)
		{
			__this = obj;
			if($(__this).attr('data-attr') == '')
			{
				alert("Invalid Action");
				return false;
			}		
			//console.log(obj.class);
			/* alert(jobNo);
			alert(driverId);
			alert(jobRecID); */
			$(".img_"+jobNo).css('display','block');
			dataAttr = $(__this).attr('data-attr');
			//alert(dataAttr);
			$.ajax({
			url:"sendNotificationArrivalJob.php",
			type:"POST",
			async:false,
			data:{jobNo:jobNo,company:company,driverId:driverId,jobRecID:jobRecID,dataAttr:dataAttr},
				success: function(response){
					$(".img_"+jobNo).css('display','none');
					
					if(response == '1')
					{
						
						$("#sendpn_"+jobNo).removeClass('RedBtnLeBg');
						dataAttr = $(__this).attr('data-attr','2');
					}
					else if(response == '2')
					{
						
						$("#sendpn_"+jobNo).addClass('RedBtnLeBg');
						dataAttr = $(__this).attr('data-attr','3');
						$("#sendpn_"+jobNo).css('display','none');
					}
					
					
				}
			});
		}
</script>
</body>
</html>

	