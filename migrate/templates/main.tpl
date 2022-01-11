<?php
/**
 * Index template
 */
if (!@is_array($config)) {
    die('Hack off!');
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>step <?= $step ?></title>
        <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet">
        <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.css" rel="stylesheet">
        <link href="./static/main.css" rel="stylesheet">
    </head>
    <body>
        <div class='container'>
            <h1>Функциональность выполнения миграции</h1>

            <!--Alerts and messages-->
            <?php if (!empty($alerts)) { ?>
                <?php foreach ($alerts as $alert) { ?>
                    <div class="alert alert-<?= $alert[0] ?>">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <?= $alert[1] ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <!--//Alerts and messages-->

            <!--Main block-->
            <?php require_once("./templates/step_$step.tpl"); ?>
            <!--//Main block-->

            <?php if (!empty($dump)) var_dump($dump); ?>
        </div>
        <!--scripts-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.js"></script>
        <script src="./static/file.js"></script>
        <script src="./static/main.js"></script>
        <!--//scripts-->
    </body>
</html>
