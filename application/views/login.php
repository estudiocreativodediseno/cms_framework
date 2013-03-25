<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/css/base.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/css/login.css" />
		
		<script type="text/javascript" src="<?php echo base_url(); ?>resources/js/libraries/jquery-1.9.1.min.js"></script>
		<!-- <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/login.js"></script> -->
		<!--<script type="text/javascript" src="scripts/analytics.js"></script>-->

			<title></title>
		
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

		<meta name="description" content="" />
		<meta name="keywords" content="" />

		<!--[if lt IE 9]><link href="styles/ie.css" rel="stylesheet" type="text/css" /><![endif]-->
	</head>

	<body class="app display">
		<div class="wrap">
			<div class="main clearfix">
				<div class="login">
					<img src="<?php echo base_url(); ?>resources/img/ui/login-img.png" class="left" />

                       	<?php 
					   		echo validation_errors(); 
							$options=array('class'=>'login-form right','autocomplete'=>'off');
					   		echo form_open('Login/processLogin',$options);
						?>
                       
						<fieldset>
							<label for="">Usuario</label>
							<input type="text" size="20" id="username" name="username"/>
						</fieldset>

						<fieldset>
							<label for="">Contraseña</label>
							<input type="password" size="20" id="passowrd" name="password"/>
						</fieldset>

						<div class="helper-box left">
							<!-- a href="#" class="txt-btn">Olvidé mi contraseña <span class="helper right"></span></a> -->
                            
							 <?php if(isset($msg)) echo $msg;?>
						</div> 
						
						<input type="image" src="<?php echo base_url(); ?>resources/img/buttons/transparent.gif" class="sprite btn entrar-btn right" value="" />
					</form>
				</div>
			</div>

			<div class="footer-bar"></div>
		</div>
	</body>
</html>