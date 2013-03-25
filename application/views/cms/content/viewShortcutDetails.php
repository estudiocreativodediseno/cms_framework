<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/tables.css" />
<script src="<?php echo base_url(); ?>resources/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>resources/js/jquery-litelighter.js"></script>
<script src="<?php echo base_url(); ?>resources/js/jquery-litelighter-extra.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    
</head>		

<body>
	
                <div id="sc-line-numbers" style="float:left; width:45px;">
                    <?php for($i=0; $i<count($lines); $i++){ ?>
                        <div class="line-number"><code><pre class="shortcut-pre"><?php echo ($i+1); ?></pre></code></div>
                    <?php } ?>
                </div>
                <div id="sc-line-codes" style="float:left; width:450px; overflow-x: scroll;">
                    <?php $copy = ''; $k=1; foreach($lines as $line){ ?>                        	
                        <div class="editable-line" id="line-<?php echo $k++; ?>">
                            <code><pre class="shortcut-pre" data-lllanguage="htmlphp"><?php echo htmlspecialchars(str_replace("\t", "	", $line),ENT_QUOTES); ?></pre></code>
                        </div>    
						<?php $copy .= str_replace("/'/","T",htmlspecialchars(str_replace("\t", "	", $line),ENT_QUOTES)); ?>
                        
                        <input type="hidden" id="shortcutCodeLines" value="<?php echo htmlspecialchars(str_replace("\t", "	", $line),ENT_QUOTES); ?>"/>
                    <?php } ?>
                </div>
                
                
                <div style="clear:both; height:10px;"/>
                <select id="cmbStructureId" <?php if($isStructure==0) echo 'style="display:none;"'; ?>>
                	<option value="">Seleccione ... </option> 
						<?php foreach($structures as $structure){ ?>                        	
                            <option value="<?php echo md5($structure['structuresId']); ?>"><?php echo $structure['name']; ?></option>
                        <?php } ?>
                </select>
                <div <?php if($isField==0) echo 'style="display:none;"'; ?>>
                    <select id="cmbDataType">
                        <option value="">...</option>
                    </select>
				</div>
                <input type="hidden" id="isStructure" 	name="isStructure" 	value="<?php echo $isStructure; ?>"/>
                <input type="hidden" id="isField" 		name="isField" 		value="<?php echo $isField; ?>"/>
                <input type="hidden" id="shortcutCode" 	name="shortcutCode" value="<?php echo $isField; ?>"/>
                
                <input type="button" value="Agregar" class="action-btn default-btn" onclick="addShortcutCode();"/>
                


</body>
</html>