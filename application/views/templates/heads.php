
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>CMS - <?php echo $title; ?></title>
        <?php if(isset($output)){ ?>
			<?php if(isset($output->css_files)){ ?>
				<?php foreach($output->css_files as $file): ?>
                    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
                <?php endforeach; ?>
			<?php } ?>
                    <link rel="icon" href="<?php echo base_url(); ?>resources/img/favicon.ico" type="image/x-icon" />
                    <link rel="shortcut icon" href="<?php echo base_url(); ?>resources/img/favicon.ico" type="image/x-icon" />
            		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/base.css" />
            		<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans" />
            		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/jquery.qtip.min.css" />
                
			<?php if(isset($output->js_files)){ ?>
                <?php foreach($output->js_files as $file): ?>
                    <script src="<?php echo $file; ?>"></script>
                <?php endforeach; ?>
			<?php } ?>

		<?php } ?>

                    <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/libraries/jquery.collapse.js"></script>
                    <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/libraries/jquery.easing.js"></script>
                    <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/app-chrome.js"></script>
                    <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/tools-manager.js"></script>
					<script type="text/javascript" src="<?php echo base_url(); ?>resources/js/init.js"></script>
                    <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery.qtip.min.js"></script>
                    <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery.qtip.grow.js"></script>
                    
              
	</head>