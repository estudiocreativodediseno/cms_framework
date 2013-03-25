<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>CMS - {title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        {metas}
        {scripts}
        {styles}
        <link rel="icon" href="<?= base_url() ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?= base_url() ?>favicon.ico" type="image/x-icon" />
    </head>
    <body>
        <div class="header">
            {header}
        </div>
        <div class="left-panel">
            {left-panel}
        </div>
        <div class="content">
            {content}
        </div>
        <div class="footer">
            {footer}
        </div>
    </body>
</html>