<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title><?php echo isset($title)?$title:$core->name; ?></title>
<?php echo HTML::script('media/jquery-2.2.4.min.js'); ?>
<?php echo HTML::style('media/pure-min.css'); ?>
<?php echo HTML::style('media/default.css'); ?>
</head>
<body>
<div class="pure-g">
<?php echo isset($body)?$body:'-'; ?>
</div>
</body>
</html>
