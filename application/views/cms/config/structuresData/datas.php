                    <!-- Inicia workspace -->
                    <div class ="workspace" id="workspace">
                    
                            <!-- Inicia module-title -->
                            <div class="module-title">
                                <div class="displayname-module">
									<a href="<?php echo base_url(); ?>index.php/cms/config/StructuresData/">Regresar</a>
                                </div>
                                <div class="icon-module" style="width:80px;">
									<img width="100%" src="<?php echo base_url().'/uploads/files/images/'.$moduleData->urlIconImage; ?>" />
                                </div>
                                <div class="displayname-module">
									<?php echo $moduleData->displayName; ?>
                                </div>
                            </div>
                            <!-- Termina title-area -->
                        <?php echo $output; ?>
                    </div>
                    <!-- Termina workspace -->
            </div>
            <!-- Termina main-content -->
              