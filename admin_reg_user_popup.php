<?
 include "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="js/sitemapstyler.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="table.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<title>BCP Check In ฟินรับทอง</title>
</head>

<style type="text/css">
.center {
  margin-left: auto;
  margin-right: auto;
}
</style>
<body>

<?
	echo "<h6 style='font-size:33px; text-align:center; color:#709898;'>BCP Check In ฟินรับทอง</h6>"; 

?><p></p>



<form action="register_act.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="return formValidate()" style="text-align:center;" class="center">

	<table cellpadding="0" cellspacing="0" border="0" id="example"  style="text-align:center;" class="center">    <thead>  

    </thead> 
		<thead>  
			<tr>  
				<th width="350" colspan="2"><h_table>Register</h_table></th>  	
			</tr>  
		</thead> 
         
		<tbody>  
			<tr>  
			   <td width="150 px" ><h6 id="text_new_system">Name</h6></td>
			   <td width="150 px" colspan="3"><h6><textarea name="Name" id="Name" style="width:100%" placeholder="ชื่อผู้ลงทะเบียน"></textarea></h6></td>       

			</tr>  	
			 <tr>  
			   <td width="150 px" ><h6 id="text_new_system_en">Surname</h6></td>
			   <td width="150 px" colspan="3"><h6><textarea name="Surname" id="Surname" style="width:100%" placeholder="นามสกุลผู้ลงทะเบียน"></textarea></h6></td>    
			</tr> 
			<tr>  
			   <td width="150 px" ><h6 id="text_new_system">Tel</h6></td>
			   <td width="150 px" colspan="3"><h6><textarea name="Tel" id="Tel" style="width:100%" placeholder="เบอร์โทรศัพท์"></textarea></h6></td>       

			</tr>  	
			 <tr>  
			   <td width="150 px" ><h6 id="text_new_system_en">E-Mail</h6></td>
			   <td width="150 px" colspan="3"><h6><textarea name="mail" id="mail" style="width:100%" placeholder="E-Mail"></textarea></h6></td>    
			</tr> 

			<tr style="text-align:center;">  
				<td colspan="4" width="260 px" style="text-align:center;">
					<input type="submit" name="submit" value="Submit" style="color:red;" onClick="setButtonType('save'); return confirm('Are you sure you want to Save survey?');"  >
					<!--<input type="submit" name="submit" value="Submit" style="color:red;" / >-->
					<input type="hidden" name="button_type" id="button_type" value="null_value" />
				</td>                
			</tr>   

			</table>
			
	<p></p>
  






</tbody>  
    <tfoot>  
     <!--   <tr>  
            <th>Rendering engine</th>  
            <th>Browser</th>  
            <th>Platform(s)</th>  
            <th>Engine version</th>  
            <th>CSS grade</th>  
        </tr>  -->
    </tfoot>  
    
    </table>



	
 <script type="text/javascript">
 
setTimeout("call();",0);

function call()
{
 //alert("เพิ่มข้อมูลเรียบร้อยแล้ว");
// window.location='/bcp_csr/mainuser_admin.php';
}
 
 


  
</script>
  
</body>
</html>