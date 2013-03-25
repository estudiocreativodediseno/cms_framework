



                    		<input type="hidden" name="base-url" id="base-url" value="<?php echo base_url(); ?>" />
                    <!-- Inicia aside -->
                    <div class="aside">
                    
                    	<ul>
					<?php
						$moduleContents = 49;
						$modulesId = (isset ($this->session->userdata['moduleData']->modulesId)) ? $this->session->userdata['moduleData']->modulesId : 0;
						$menu = $this->CmsPermission->getAllowedUserModules();
					
						foreach ($menu->modules as $m1) {
								$c1=0;
								echo '
										<li'.($modulesId==$m1['moduleId']?' style="text-decoration:underline;"':'').'>'. 
											'<a href="'.base_url().'index.php'.$m1['urlSection'].'?mod='.md5($m1['moduleId']).'" alt="'.$m1['description'].'" title="'.
																				$m1['description'].'">'.$m1['displayName'].'</a>'.'</li>';
									if($m1['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
							foreach ($m1['modules'] as $m2) {
									$c2=0;
									echo ($c1++==0?'
										<ul>':'').'
											<li'.($modulesId==$m2['moduleId']?' style="text-decoration:underline;"':'').'>'. 
												'<a href="'.base_url().'index.php'.$m2['urlSection'].'?mod='.md5($m2['moduleId']).'" alt="'.$m2['description'].'" title="'.
																				$m2['description'].'">'.$m2['displayName'].'</a>'.'</li>';
									if($m2['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
								foreach ($m2['modules'] as $m3) {
									$c3=0;
									echo ($c2++==0?'
										<ul>':'').'
											<li'.($modulesId==$m3['moduleId']?' style="text-decoration:underline;"':'').'>'. 
												'<a href="'.base_url().'index.php'.$m3['urlSection'].'?mod='.md5($m3['moduleId']).'" alt="'.$m3['description'].'" title="'.
																				$m3['description'].'">'.$m3['displayName'].'</a>'.'</li>';
									if($m3['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
									foreach ($m3['modules'] as $m4) {
										$c4=0;
										echo ($c3++==0?'
											<ul>':'').'
												<li'.($modulesId==$m4['moduleId']?' style="text-decoration:underline;"':'').'>'. 
													'<a href="'.base_url().'index.php'.$m4['urlSection'].'?mod='.md5($m4['moduleId']).'" alt="'.$m4['description'].'" title="'.
																				$m4['description'].'">'.$m4['displayName'].'</a>'.'</li>';
										if($m4['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
										foreach ($m4['modules'] as $m5) {
											echo ($c4++==0?'
												<ul>':'').'
													<li'.($modulesId==$m5['moduleId']?' style="text-decoration:underline;"':'').'>'. 
														'<a href="'.base_url().'index.php'.$m5['urlSection'].'?mod='.md5($m5['moduleId']).'"
															 alt="'.$m5['description'].'" title="'.	$m5['description'].'">'.$m5['displayName'].'</a>'.'</li>';
											if($m5['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
										}
										if($c4>0)		echo '
												</ul>';
									}
									if($c3>0)		echo '
											</ul>';
								}
								if($c2>0)		echo '
										</ul>';
							}
							if($c1>0)		echo '
									</ul>';
						}	
						
						
					?>
                        
                        </ul>
                    </div>
                    <!-- Termina aside -->
                    
              
              
              
              
            