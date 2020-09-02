<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>BCP Check In</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Expand, contract, animate forms with jQuery wihtout leaving the page" />
        <meta name="keywords" content="expand, form, css3, jquery, animate, width, height, adapt, unobtrusive javascript"/>
		<meta name="viewport" content="width=device-width,initial-scale=1.0"/> 
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="css_login/style.css" />
		<script src="js_login/cufon-yui.js" type="text/javascript"></script>
		<script src="js_login/ChunkFive_400.font.js" type="text/javascript"></script>
		<script type="text/javascript">
			Cufon.replace('h1',{ textShadow: '1px 1px #fff'});
			Cufon.replace('h2',{ textShadow: '1px 1px #fff'});
			Cufon.replace('h3',{ textShadow: '1px 1px #000'});
			Cufon.replace('.back');
		</script>
        <script type="text/javascript">

function fncSubmit()
{
	
	var initial = $("#txt_initial").val();
	//var password = $("#txt_initial").val();
	var password = $("#password").val();
	var uri_enc = encodeURIComponent(password)
	//alert("ยินดีต้อนรับ "+uri_enc );
	//window.event.returnValue = false;
	//var urldd ='test.php?user_initial='+initial+'&password='+password;
	//alert(uurldd);
	//window.location.assign(urldd);
	//window.location= "../test.php?user_initial="+initial+"&password="+password;
	window.location.href = "connect_ad.php?user_initial="+initial+"&password="+uri_enc;
	//window.location.href = "test.php";
	//alert(initial);
}



</script>
    </head>
    <body>
		<div class="wrapper">
			<h1 style="text-align:center" >BCP Check In ฟินรับทอง</h1>
			<a ></a>
			<p></p>
			<p></p>
			<p></p>
			<p></p>
			<p></p>

			<div class="content">
				<div id="form_wrapper" class="form_wrapper">
					<form class="login active">
						<h3>Register</h3>
						<div>
							<label>Name:</label>
							<input type="text" id="txt_initial" />
							<span class="error">This is an error</span>
						</div>
						<div>
							<label>Surname:</label>
							<input type="text" id="txt_initial" />
							<span class="error">This is an error</span>
						</div>
						<div>
							<label>E-mail:</label>
							<input type="text" id="txt_initial" />
							<span class="error">This is an error</span>
						</div>
						<div>
							<label>Name:</label>
							<input type="text" id="txt_initial" />
							<span class="error">This is an error</span>
						</div>

						<div class="bottom">
							<div class="remember"><!--<input type="checkbox" /><span>Keep me logged in</span>--></div>
							<input type="submit" value="Login" onClick="fncSubmit()" ></input>
							<a></a>
							<div class="clear"></div>
						</div>
					</form>

			 
					<div style="padding-top:25px;">
						<img src="pic/com_01.png" width="150px"; style="padding-left:25%;" >
					</div>
				</div>
				

				<div class="clear"></div>
				
				
			</div>
			<a class="back" href=""> </a>
		</div>
		
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<!-- The JavaScript
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->
		<script type="text/javascript">

			$(function() {
					//the form wrapper (includes all forms)
				var $form_wrapper	= $('#form_wrapper'),
					//the current form is the one with class active
					$currentForm	= $form_wrapper.children('form.active'),
					//the change form links
					$linkform		= $form_wrapper.find('.linkform');
						
				//get width and height of each form and store them for later						
				$form_wrapper.children('form').each(function(i){
					var $theForm	= $(this);
					//solve the inline display none problem when using fadeIn fadeOut
					if(!$theForm.hasClass('active'))
						$theForm.hide();
					$theForm.data({
						width	: $theForm.width(),
						height	: $theForm.height()
					});
				});
				
				//set width and height of wrapper (same of current form)
				setWrapperWidth();
				
				/*
				clicking a link (change form event) in the form
				makes the current form hide.
				The wrapper animates its width and height to the 
				width and height of the new current form.
				After the animation, the new form is shown
				*/
				$linkform.bind('click',function(e){
					var $link	= $(this);
					var target	= $link.attr('rel');
					$currentForm.fadeOut(400,function(){
						//remove class active from current form
						$currentForm.removeClass('active');
						//new current form
						$currentForm= $form_wrapper.children('form.'+target);
						//animate the wrapper
						$form_wrapper.stop()
									 .animate({
										width	: $currentForm.data('width') + 'px',
										height	: $currentForm.data('height') + 'px'
									 },500,function(){
										//new form gets class active
										$currentForm.addClass('active');
										//show the new form
										$currentForm.fadeIn(400);
									 });
					});
					e.preventDefault();
				});
				
				function setWrapperWidth(){
					$form_wrapper.css({
						width	: $currentForm.data('width') + 'px',
						height	: $currentForm.data('height') + 'px'
					});
				}
				
				/*
				for the demo we disabled the submit buttons
				if you submit the form, you need to check the 
				which form was submited, and give the class active 
				to the form you want to show
				*/
				$form_wrapper.find('input[type="submit"]')
							 .click(function(e){
								e.preventDefault();
							 });	
			});
        </script>
    </body>
</html>