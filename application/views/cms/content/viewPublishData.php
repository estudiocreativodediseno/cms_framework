<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/tables.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
</head>		

<body>



 <div class="tsc_pricingtable04">
    <ul class="price-box">
      <li class="pricing-header">
        <ul>
            <li class="month-label"></li>
        </ul>
      </li>
      <li class="pricing-content">
        <ul>
          
          <?php
			foreach ($data as $row){
		  ?>
				<li>
					<strong><?php echo $row->displayName; ?></strong>
                    <div class="separator">
						<?php echo $row->data; ?>
                    </div>
           		</li>
          <?php
			}
		  ?>
        </ul>
      </li>
      <li class="pricing-footer"> <a href="#" class="buy-now">Cerrar</a> </li>
    </ul>
      
  </div>
<!-- DC Pricing Tables:4 End -->
<div class="tsc_clear"></div> <!-- line break/clear line -->
<?php 
			/*	foreach ($data as $row)
				echo '-'.$row->displayName.': <strong>'.$row->data.'</strong><br>';
				print_r($row);
				*/
			
?>
</body>
</html>