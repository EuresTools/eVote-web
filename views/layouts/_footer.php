<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use app\components\languageSwitcher;

echo Html::beginTag('footer', ['class'=>'footer']);

NavBar::begin([
    'brandLabel' => '&copy; Eurescom GmbH '.date('Y'),
    'brandUrl' => null,
    'options' => [
        'class' => 'navbar-default navbar-fixed-bottom',
    ],
]);


if (Yii::$app->params['multilanguage-app']) {
    echo languageSwitcher::Widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    ]);
}

NavBar::end();
echo Html::endTag('footer');
