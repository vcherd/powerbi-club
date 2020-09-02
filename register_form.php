<?
 include "connectdatabase.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>

<style type="text/css">
.error{
	color:#F00;
	padding-left:5 px;
	font-size:14px;
	}
</style>
<body>
<!--<form action="new_csr_activity.php" name="frmMain" method="post" enctype="multipart/form-data" target="iframe_target" onSubmit="return ChkSubmit();">-->

<form id="form1" name="form1" method="get" action="admin_add_user_act.php">

<div id="divresult"></div>
<div id="progress" style="visibility:hidden"><img src="progress.gif"></div>

<iframe id="iframe_target" name="iframe_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
<div class="CSSTableGenerator">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="410" height="260">    
<thead>  
		<tr >  
            <th width="150 px" height="28" colspan="2" ><h7>Register</h7></th>
		</tr>
		<tr >  
            <th width="150 px" ><h6>Name - Surname</h6></th>
			<th width="260 px"><h6><input type="text" name="emp_id" id="emp_id" style="width:250px"><span id="errorName" class="error"></span></h6></th>                
        </tr>
        <tr >  
            <th width="150 px" ><h6>Tel</h6></th>
			<th width="260 px"><h6><input type="text" name="emp_initial" id="emp_initial" style="width:250px"><span id="errorName" class="error"></span></h6></th>                
        </tr>
		<tr >  
            <th width="150 px" ><h6>E-Mail</h6></th>
			<th width="260 px"><h6><input type="text" name="first_name" id="first_name" style="width:250px"><span id="errorName" class="error"></span></h6></th>                
        </tr>
 
    </thead> 
 </table>
 </form>
 </div>

  
</body>
</html>