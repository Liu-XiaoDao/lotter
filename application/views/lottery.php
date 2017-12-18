<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $year, ' 抽奖活动现场 - ', isset($title)?$title:$core->name; ?></title>
<?php echo HTML::script('media/jquery-3.1.1.min.js'); ?>
<?php echo HTML::script('media/three/three.min.js'); ?>
<?php echo HTML::script('media/three/Detector.js'); ?>
<?php echo HTML::script('media/three/tween.min.js'); ?>
<style>
body, html { padding: 0; margin: 0; font-size: 0; line-height: 0; background: url('') #000 center center no-repeat;}
</style>
<body>
<?php echo isset($body)?$body:null; ?>
</body>
</html>
