<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\widgets\AlertBlock;

/* @var $this \yii\web\View */
/* @var $content string */

// <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
// <meta name="viewport" content="width=device-width, initial-scale=1">
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php
    if ($this->context->id==='vote' && Yii::$app->params['itunes-app-id']) {
            $token = Yii::$app->request->get('token');
            $itunes_content= "app-id=".Yii::$app->params['itunes-app-id'];
            $itunes_content.= " app-argument=".Yii::t('app', Yii::$app->params['itunes-app-argument'], ['token' => $token]);
            $this->registerMetaTag(['name' => 'apple-itunes-app', 'content' => $itunes_content]);

        /*
        if (Yii::$app->request->get('token')) {
            // add meta link with token
            $this->registerMetaTag(['name' => 'apple-itunes-app', 'content' => 'This is my cool website made with Yii!']);
        } else {
            // add meta link without token
            $this->registerMetaTag(['name' => 'apple-itunes-app', 'content' => 'This is my cool website made with Yii!']);

            <meta name="apple-itunes-app" content="app-id=992815164,  app-argument=evote://">
        }
        */
    }
    ?><meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ? Html::encode(Yii::$app->name." | $this->title") : (Yii::$app->name.' | Home') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php
    echo AlertBlock::widget([
        'useSessionFlash' => true,
        'type' => AlertBlock::TYPE_GROWL,
        'delay' => false, // Don't automatically disappear.
    ]);
?>

<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
