
	<body class="app workarea">
		<div class="wrap" id="wrapper">        
        	<!-- Inicia Wrapper -->
                <!-- Inicia header -->
                <div class="header-bar">
                    <div class="header clearfix">
                        <div class="user-bar">
                            <ul class="user">
                                <li><span class="user-name">Bienvenido</span> <?php echo $name; ?> (<?php echo $username; ?>)</li>
                                <li><a href="<?php echo base_url(); ?>index.php/login/logout" class="btn sprite exit-btn">Salir</a></li>
                            </ul>
                            <div class="brand"></div>
    
                        </div>
    
                        <div class="main-nav clearfix">
                            <ul class="tabs right">
                            <?php
                            	$menu = $this->CmsPermission->getAllowedUserModules();
					
								foreach ($menu->modules as $m1) :
										$c1=0;
							?>
                                <li>
                                	<?php
                                     echo '<a class="tab" href="'.base_url().'index.php'.$m1['urlSection'].'?mod='.md5($m1['moduleId']).'" alt="'.$m1['description'].
									 				'" title="'.$m1['description'].'">'
															.$m1['displayName'].
											'</a>';
									?>
                                </li>
                            <?php
								endforeach;
							?>
                            </ul>
                        </div>
                    </div>
                </div>
