                   
                   	<input type="hidden" name="base-url" id="base-url" value="<?php echo base_url();?>" />
                    <!-- Inicia workspace -->
                    <span id="workspace">
                    
                    	<div class="workspace title">Permisos para: <strong><?php if(isset($groupName)) echo $groupName; ?></strong></div>
                    	<ul>
                        <?php
							$parentM = ""; 
							$counter = $level = 0; 
							foreach ($rs_array->result() as $row){	?>			
                        	<li>
                            	<span id="module-lock-image-<?php echo $groupId.'-'.$row->functionsId;?>" class="module-lock-image">
								<?php 
									if(is_null($row->userGroupsId)){
										?> <img src="<?php echo base_url();?>/images/icons/cancel.png" /> <?php 
									}else{ 
										?> <img src="<?php echo base_url();?>/images/icons/ok.png" /> <?php 
									}
								?> 
                                </span> 
                            	<span class="module-displayname">
								<?php 
									echo $row->displayName;
								?> 
                                </span> 
                            	<span id="module-link-function-<?php echo $groupId.'-'.$row->functionsId;?>" class="module-link-function">
								<?php 
									if(is_null($row->userGroupsId)){
										?> [<a href="#allow" onclick="editFunctionAllowed('add','<?php echo $groupId;?>','<?php echo $row->functionsId;?>');">Permitir</a>]<?php 
									}else{ 
										?> [<a href="#denny" onclick="editFunctionAllowed('remove','<?php echo $groupId;?>','<?php echo $row->functionsId;?>');">Denegar</a>]<?php 
									}
									$counter++;
								?>
							</li>
						<?php } ?>
                        </ul>
                    </span>
                    <!-- Termina workspace -->
            </span>
            <!-- Termina main-content -->
              