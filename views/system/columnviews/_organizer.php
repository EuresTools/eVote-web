<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Organizer;

if (isset($data)) {
    $name = ArrayHelper::getValue($data, 'name');
    $id = ArrayHelper::getValue($data, 'id');

    if (Yii::$app->user->identity->isAdmin()) {
        echo Html::a(
            $name,
            ['//organizer/view', 'id' => $id],
            ['title'=>'View organizer detail']
        );
    } else {
        echo $name;
    }
}
