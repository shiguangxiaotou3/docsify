<?php
/** @var array $main  */
/** @var array $css  */
/** @var array $js  */
?>
<!DOCTYPE html>
<html lang="<?= get_bloginfo('language') ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
<?php foreach ($css as $item){ ?>
    <link rel="stylesheet" href="<?= $item ?>">
<?php  } ?>
</head>
<body>

    <div id="app">请稍候...</div>
    <script type="text/javascript">
        window.$docsify =<?= json_encode($main, JSON_PRETTY_PRINT) ?>
    </script>
<?php foreach ($js as $item){ ?>
    <script src="<?= $item ?>" type="text/javascript"></script>
<?php  } ?>
</body>
</html>