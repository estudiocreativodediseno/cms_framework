                    <!-- Inicia workspace -->
                    <div class ="workspace" id="workspace">
                        <div class="inner resize clearfix">
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
                        <?php echo $output; ?>
                        </div>
                    </div>
                    <!-- Termina workspace -->
            </div>
            <!-- Termina main-content -->
              