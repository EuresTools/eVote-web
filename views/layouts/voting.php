<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\widgets\AlertBlock;

$this->beginContent('@app/views/layouts/main.php'); ?>
<div class="wrap">
    <?php
    // don't display the menue for Guest Users but display it for already logined admin
    if (!Yii::$app->user->isGuest) {
        echo $this->render('@app/views/layouts/_menue');
    }
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>
<?= $this->render('@app/views/layouts/_footer');?>
<?php $this->endContent();
