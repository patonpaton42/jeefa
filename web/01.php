<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to Symfony!</title>
  </head>
<body>
<h1><?php echo $page_title ?></h1>
<ul id="navigation">
<?php foreach ($navigation as $item): ?>
<li>
<a href="<?php echo $item->getHref() ?>">
<?php echo $item->getCaption() ?>
</a>
</li>
<?php endforeach; ?>
</ul>
</body>
</html>
