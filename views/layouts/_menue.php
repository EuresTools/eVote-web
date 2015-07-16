<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => 'eVote',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$items = [];
if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()) {
    $items[] = ['label' => 'Users', 'url' => ['/user']];
    $items[] = ['label' => 'Organizers', 'url' => ['/organizer']];
}
if (Yii::$app->user->identity && Yii::$app->user->identity->isOrganizer()) {
    $items[] = ['label' => 'Polls', 'url' => ['/poll']];
}
if (Yii::$app->user->isGuest) {
    $items[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $items[] = ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']];
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $items,
]);
NavBar::end();
