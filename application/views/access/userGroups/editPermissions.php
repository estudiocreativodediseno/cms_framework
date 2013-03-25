                   
                   	<input type="hidden" name="base-url" id="base-url" value="<?php echo base_url();?>" />
                    <!-- Inicia workspace -->
                    <div id="workspace">
                    
                    	<div class="workspace title">Permisos para: <strong><?php if(isset($groupName)) echo $groupName; ?></strong></div>
                    	<ul>
					<?php
						$spanImage 			= '<span id="module-lock-image-'.$groupId.'-%modulesId%" class="module-lock-image">%image%</span>';
						$image 				= '<img src="'.base_url().'/images/icons/lock%active%.png" />'; 
						$spanDisplayName 	= '<span class="module-displayname%">%displayName%</span>';
						$spanLinkFunction	= '<span id="module-link-function-'.$groupId.'-%modulesId%" class="module-link-function">%linkFunction%</span>';
						$linkFunction		= ' [<a href="#edit" onclick="editPermission(\'%action%\',\''.$groupId.'\',\'%modulesId%\');">%aName%</a>]';
						//(is_null($row->userGroupsId)?'add':'remove').
						//(is_null($row->userGroupsId)?'Permitir':'Denegar')
						foreach ($modules as $m1) {
							$c1=0;
							echo '
									<li>'. 	
										str_replace('%image%', str_replace('%active%',(is_null($m1['userGroupId'])?'On':'Off'),$image),
													str_replace('%modulesId%',$m1['moduleId'],$spanImage) ) .
										str_replace('%displayName%',$m1['displayName'],$spanDisplayName).
										str_replace('%linkFunction%', 
															str_replace('%action%',(is_null($m1['userGroupId'])?'add':'remove'),
																str_replace('%aName%',(is_null($m1['userGroupId'])?'Permitir':'Denegar'),
																	str_replace('%modulesId%',$m1['moduleId'],$linkFunction))),
													str_replace('%modulesId%',$m1['moduleId'],$spanLinkFunction) ).
										'</li>';
							foreach ($m1['modules'] as $m2) {
								$c2=0;
								echo ($c1++==0?'
									<ul>':'').'
										<li>'. 	
											str_replace('%image%', str_replace('%active%',(is_null($m2['userGroupId'])?'On':'Off'),$image),
														str_replace('%modulesId%',$m2['moduleId'],$spanImage) ) .
											str_replace('%displayName%',$m2['displayName'],$spanDisplayName).
											str_replace('%linkFunction%', 
																str_replace('%action%',(is_null($m2['userGroupId'])?'add':'remove'),
																	str_replace('%aName%',(is_null($m2['userGroupId'])?'Permitir':'Denegar'),
																		str_replace('%modulesId%',$m2['moduleId'],$linkFunction))),
														str_replace('%modulesId%',$m2['moduleId'],$spanLinkFunction) ).
											'</li>';
								foreach ($m2['modules'] as $m3) {
									$c3=0;
									echo ($c2++==0?'
										<ul>':'').'
											<li>'. 	
												str_replace('%image%', str_replace('%active%',(is_null($m3['userGroupId'])?'On':'Off'),$image),
															str_replace('%modulesId%',$m3['moduleId'],$spanImage) ) .
												str_replace('%displayName%',$m3['displayName'],$spanDisplayName).
												str_replace('%linkFunction%', 
																	str_replace('%action%',(is_null($m3['userGroupId'])?'add':'remove'),
																		str_replace('%aName%',(is_null($m3['userGroupId'])?'Permitir':'Denegar'),
																			str_replace('%modulesId%',$m3['moduleId'],$linkFunction))),
															str_replace('%modulesId%',$m3['moduleId'],$spanLinkFunction) ).
												'</li>';
									foreach ($m3['modules'] as $m4) {
										$c4=0;
										echo ($c3++==0?'
											<ul>':'').'
												<li>'. 	
													str_replace('%image%', str_replace('%active%',(is_null($m4['userGroupId'])?'On':'Off'),$image),
																str_replace('%modulesId%',$m4['moduleId'],$spanImage) ) .
													str_replace('%displayName%',$m4['displayName'],$spanDisplayName).
													str_replace('%linkFunction%', 
																		str_replace('%action%',(is_null($m4['userGroupId'])?'add':'remove'),
																			str_replace('%aName%',(is_null($m4['userGroupId'])?'Permitir':'Denegar'),
																				str_replace('%modulesId%',$m4['moduleId'],$linkFunction))),
																str_replace('%modulesId%',$m4['moduleId'],$spanLinkFunction) ).
													'</li>';
										foreach ($m4['modules'] as $m5) {
											echo ($c4++==0?'
												<ul>':'').'
													<li>'. 	
														str_replace('%image%', str_replace('%active%',(is_null($m5['userGroupId'])?'On':'Off'),$image),
																	str_replace('%modulesId%',$m5['moduleId'],$spanImage) ) .
														str_replace('%displayName%',$m5['displayName'],$spanDisplayName).
														str_replace('%linkFunction%', 
																			str_replace('%action%',(is_null($m5['userGroupId'])?'add':'remove'),
																				str_replace('%aName%',(is_null($m5['userGroupId'])?'Permitir':'Denegar'),
																					str_replace('%modulesId%',$m5['moduleId'],$linkFunction))),
																	str_replace('%modulesId%',$m5['moduleId'],$spanLinkFunction) ).
														'</li>';
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
                    <!-- Termina workspace -->
            </div>
            <!-- Termina main-content -->
              
              
              
              