<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>


			<?php if(isset($output->js_files)){ ?>
                <?php foreach($output->js_files as $file): ?>
                    <script src="<?php echo $file; ?>"></script>
                <?php endforeach; ?>
			<?php } ?>
</head>

<body>


    <div id="line-numbers" style="float:left; width:45px;">
        <div class="line-number">1</div>
        <div class="line-number">2</div>
        <div class="line-number">3</div>
    </div>
    <div id="line-codes" style="float:left; width:850px; overflow-x: scroll;">
        <div class="editable-line" id="line-1" contenteditable="true">
            Lorem ipsum ad his scripta blandit partiendo, eum fastidii accumsan euripidis in, eum liber hendrerit an. 
        </div>
        <div class="editable-line" id="line-2" contenteditable="true">
            Qui ut wisi vocibus suscipiantur, quo dicit ridens inciderint id. Quo mundi lobortis reformidans eu, legimus 
        </div>
        <div class="editable-line" id="line-3" contenteditable="true">
            senserit definiebas an eos
        </div>
    </div>
    
    <div id="shortcut-codes" style="float:left; width:250px;">
    	<input type="button" value="For" onclick="addFunction(this.value)"/>
    	<input type="button" value="While" onclick="addFunction(this.value)"/>
    	<input type="button" value="If-Else" onclick="addFunction(this.value)"/>
    </div>
</body>
</html>