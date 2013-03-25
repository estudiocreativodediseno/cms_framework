


			<div class="main clearfix">
                <!-- Inicia aside -->
				<div class="aside left">
					<div class="tree">
                    
                    <input type="hidden" name="base-url" id="base-url" value="<?php echo base_url(); ?>" />
                    
                    	<ul id="accordion" class="accordion">
					<?php
						$moduleContents = 49;
						$modulesId = (isset ($this->session->userdata['moduleData']->modulesId)) ? $this->session->userdata['moduleData']->modulesId : 0;
						$menu = $this->CmsPermission->getAllowedUserModules();
					
						foreach ($menu->modules as $m1) {
								$c1=0;
								echo '
										<li'.($modulesId==$m1['moduleId']?' style="/*text-decoration:underline*/;"':'').'>'. 
											'<a href="'.base_url().'index.php'.$m1['urlSection'].'?mod='.md5($m1['moduleId']).'" alt="'.$m1['description'].'" title="'.
																				$m1['description'].'" '.($modulesId==$m1['moduleId']?' class="current"':'').'>'.$m1['displayName'].'</a>';
									if($m1['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
							foreach ($m1['modules'] as $m2) {
									$c2=0;
									echo ($c1++==0?'
										<ul>':'').'
											<li>'. 
												'<a href="'.base_url().'index.php'.$m2['urlSection'].'?mod='.md5($m2['moduleId']).'" alt="'.$m2['description'].'" title="'.
																				$m2['description'].'" '.($modulesId==$m2['moduleId']?' class="current"':'').'>'.$m2['displayName'].'</a>';
									if($m2['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
								foreach ($m2['modules'] as $m3) {
									$c3=0;
									echo ($c2++==0?'
										<ul>':'').'
											<li>'. 
												'<a href="'.base_url().'index.php'.$m3['urlSection'].'?mod='.md5($m3['moduleId']).'" alt="'.$m3['description'].'" title="'.
																				$m3['description'].'" '.($modulesId==$m3['moduleId']?' class="current"':'').'>'.$m3['displayName'].'</a>';
									if($m3['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
									foreach ($m3['modules'] as $m4) {
										$c4=0;
										echo ($c3++==0?'
											<ul>':'').'
												<li>'. 
													'<a href="'.base_url().'index.php'.$m4['urlSection'].'?mod='.md5($m4['moduleId']).'" alt="'.$m4['description'].'" title="'.
																				$m4['description'].'" '.($modulesId==$m4['moduleId']?' class="current"':'').'>'.$m4['displayName'].'</a>';
										if($m4['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
										foreach ($m4['modules'] as $m5) {
											echo ($c4++==0?'
												<ul>':'').'
													<li>'. 
														'<a href="'.base_url().'index.php'.$m5['urlSection'].'?mod='.md5($m5['moduleId']).'"
															 alt="'.$m5['description'].'" title="'.	$m5['description'].'" '.($modulesId==$m5['moduleId']?' class="current"':'').'>'.$m5['displayName'].'</a>';
											if($m5['moduleId']==$moduleContents)	echo $this->CmsPermission->getHtmlAllowedUserContents();
										}
										if($c4>0)		echo '</li>
												</ul>';
									}
									if($c3>0)		echo '</li>
											</ul>';
								}
								if($c2>0)		echo '</li>
										</ul>';
							}
							if($c1>0)		echo '</li>
									</ul>';
						}	
						
						
					?>
                        
                        </ul>
                    </div>
                    </div>
                    <!-- Termina aside -->
                    
              
              
              
              
            