<?php
$theme_array = ['astronomy','geology','gyroscope','literature','marketing',
        'mathematics','music','polit','agrobiologia','law','psychology',
        'geography','physics','philosophy','philosophy','chemistry','estetica',
        'error','wrong','oops','stupid',
    ];
$theme = $theme_array[array_rand($theme_array)];
$client=new SoapClient('http://yii.dev/index.php?r=referat/wsdl&t='.mktime(true));