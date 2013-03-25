                    <!-- Inicia workspace -->
                    <div class ="workspace" id="workspace">
                    
                            
                            <div class="inner resize clearfix">
                                <div class="inner-header clearfix">
                                    <div class="title-bar">
                                        <h2>HTML Titulo lorem ipsum</h2>
                                        <h3>Sub Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h3>
                                    </div>
        
                                    <div class="date-box">
                                        <p>Última actualización: 01:22 - 19 Mar 2013</p>
                                        <p>PUBLICACIÓN PERMANENTE</p>
                                    </div>
                                </div>
        
                                <div class="editor-panel">
                                
                                        <div class="versions-panel">
                                            <ul class="controller nav-bar clearfix" style="overflow:visible;">
                                                <li>
                                                    <a href="#" class="action-btn cancel-btn" onclick="viewVersionCode('<?php echo $sectionId;; ?>');">Consultar</a>
                                                </li>
                                                <li>
                                                    <select id="cmbVersions" name="cmbVersions"  data-placeholder="Versiones..." class="chzn-select" style="width:250px;">
                                                        <?php foreach($versions as $version){ ?>
                                                            <option value="<?php echo $version; ?>"><?php echo strlen($version)>0?$version:'Versión Actual'; ?></option>
                                                        <?php } ?>
                                                    </select>
                                               	</li>
                                                <li>
                                                    <label for="versionFile">Versiones</label>
                                                </li>
                                            </ul>
                                       	</div>
                                    <div class="editor colmask threecol">
                                        <div class="colmid">
                                            <div class="colleft">
        
                                                <div id="line-codes" class="col1 line-codes">
                                                    <div class="container">
                                                    
														<?php $k=1; foreach($lines as $line){ ?>                        	
                                                            <div class="editable-line" id="line-<?php echo $k++; ?>">
                                                                <code><pre class="code" data-lllanguage="htmlphp"><?php echo htmlspecialchars(str_replace("\t", "	", $line),ENT_QUOTES); ?></pre></code>
                                                            </div>    
                                                        <?php } ?>
                                                        
                                                    </div>
                                                </div><!-- Column 1 end -->
        
        
                                                <div id="line-numbers" class="col2 line-numbers">
                                                    <div class="container">
        
														<?php for($i=0; $i<count($lines); $i++){ ?>
                                                            <div class="line-number"><?php echo ($i+1); ?></div>
                                                        <?php } ?>
                                                    </div>
                                                </div><!-- Column 2 end -->
        
        
                                                <div id="action-deletes" class="col3 action-deletes">
                                                    <div class="container">
        
														<?php $k=1; foreach($lines as $line){ ?>
                                                            <div id="line-<?php echo $k; ?>-delete">
                                                                    <a class="btn delete-btn" onclick='removeLine(<?php echo $k++; ?>);' href="#delete">Borrar</a>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div><!-- Column 3 end -->
                                            </div>
                                        </div>
                                    </div>
        
        
                                    <div class="framework">
                                        <div class="container">
											<?php foreach($commands as $command){ ?>
                                                <a href="#" class="framework-shortcut red-shortcut" 
                                                	onclick="addFunction(	'<?php echo $command['shortcutsId']; ?>',
                                                    						'<?php echo $command['isStructure']; ?>',
                                                                            '<?php echo $command['isField']; ?>')">
														<?php echo $command['name']; ?></a>
                                            <?php } ?>        
                                        </div>
                                    </div>
                                </div>
        
        
                                <form id="" class="clearfix" action="" method="post">
                                    <ul class="controller nav-bar clearfix">
                                        <li><a href="#" class="action-btn save-btn" onclick="save('<?php echo $sectionId; ?>','<?php echo $file; ?>')">Guardar</a></li>
                                        <li><a href="#" class="action-btn save-btn" onclick="publish('<?php echo $sectionId; ?>')">Publicar </a></li>
                                        <li><a href="#" class="action-btn cancel-btn">Cancelar</a></li>
                                        <li><a href="#" class="action-btn cancel-btn" onclick="enableSortable();">Ordenar</a></li>
                                        <li><a href="#" class="action-btn default-btn">Ver versión</a></li>                                    
                                    	<li>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                        </div>
                
                    </div>
                    <!-- Termina workspace -->
            </div>
            <!-- Termina main-content -->
              
     