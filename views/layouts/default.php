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
    echo $this->render('@app/views/layouts/_menue');
    ?>
    <div class="container" id="content">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>
<?= $this->render('@app/views/layouts/_footer');?>
<?php $this->endContent();
