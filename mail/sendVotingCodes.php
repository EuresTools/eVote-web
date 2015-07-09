<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>

Hello <?= Html::encode($member->name) ?>,

Here is your voting code:

<?= Html::a(Html::encode($member->getCodes()->valid()->one())) ?>
