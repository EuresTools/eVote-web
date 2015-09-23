<?php
// get params with e.g. Yii::$app->params['autoforward-after']
return [
    'adminEmail' => 'admin@example.com',
    'itunes-app-id' => '992815164',
    'itunes-app-argument' => 'evote://?token={token}',
    'autoforward-after'=> '15',  // in seconds
    'readable-token-chars' => 1, // readable token chars for non admins (e.g. 1 = 1 char on front and 1 on back is visible)
    'multilanguage-app' => true,
];
