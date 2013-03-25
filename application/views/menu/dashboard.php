                    <!-- Inicia workspace -->
                    <div class ="workspace" id="workspace">
                    
                            <!-- Inicia module-title -->
                            <div class="module-title">
                                <div class="icon-module" style="width:80px;">
									<img width="100%" src="<?php echo base_url().'/uploads/files/images/'.$moduleData->urlIconImage; ?>" />
                                </div>
                                <div class="displayname-module">
									<?php echo $moduleData->displayName; ?>
                                </div>
                            </div>
                            <!-- Termina title-area -->




                                <!-- Inicia menu-dashboard -->
                                <div class="menu-dashboard">
                                
                                    <ul>
                                <?php
                                
                                    $menu = $this->CmsPermission->getAllowedSubmodules();
                                    foreach ($menu->submodules as $m1) {
									?>
                                		<li>
                                        	<span class="submenu">
                                                <span class="submenu-icon" style="display:block; width:120px;">
                                                	<a href="<?php echo base_url().'index.php'.$m1['urlSection'].'?mod='.md5($m1['moduleId']); ?>">
                                                        <img src="<?php echo base_url().'/uploads/files/images/'.$m1['urlIconImage']; ?>" width="100%" 
                                                                title="<?php echo $m1['description']; ?>" alt="<?php echo $m1['description']; ?>" />
                                                	</a>
                                                </span>
                                                <span class="submenu-name style="width:120px;">
                                                	<a href="<?php echo base_url().'index.php'.$m1['urlSection'].'?mod='.md5($m1['moduleId']); ?>">
                                                        <?php echo $m1['displayName']; ?>
                                                	</a>
                                                </span>
                                            </span>
                                        </li>
                                	<?php
                                    }	
                                    
                                    
                                ?>
                                    
                                    </ul>
                                </div>
                                <!-- Termina aside -->
                    
              






                    </div>
                    <!-- Termina workspace -->
            </div>
            <!-- Termina main-content -->
              