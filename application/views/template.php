<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title><?php echo $year, ' - ', isset($title)?$title:$core->name; ?></title>
<?php echo HTML::script('media/jquery-3.1.1.min.js'); ?>
<?php echo HTML::script('media/js/uikit.min.js'); ?>
<?php echo HTML::style('media/css/uikit.almost-flat.min.css'); ?>
<?php echo HTML::style('media/default.css'); ?>
</head>
<body>
<div class="container">
    <?php echo isset($body)?$body:'-'; ?>
</div>
</body>
</html>
