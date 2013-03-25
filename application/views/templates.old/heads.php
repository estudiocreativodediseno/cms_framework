<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>CMS - <?php echo $title; ?></title>
                    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/default.css" />
        <?php if(isset($output)){ ?>
			<?php if(isset($output->css_files)){ ?>
				<?php foreach($output->css_files as $file): ?>
                    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
                <?php endforeach; ?>
			<?php } ?>
            		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/jquery.qtip.min.css" />
                
			<?php if(isset($output->js_files)){ ?>
                <?php foreach($output->js_files as $file): ?>
                    <script src="<?php echo $file; ?>"></script>
                <?php endforeach; ?>
			<?php } ?>

		<?php } ?>
					<script src="<?php echo base_url(); ?>resources/js/init.js"></script>
                    <script src="<?php echo base_url(); ?>resources/js/jquery.qtip.min.js"></script>
                    <script src="<?php echo base_url(); ?>resources/js/jquery.qtip.grow.js"></script>
                    
        <style type='text/css'>
			body{font-family: Arial;font-size: 14px;}
			a {color: blue;text-decoration: none;font-size: 14px; }
			a:hover{text-decoration: underline;}
        </style>
	</head>