<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

// use kartik\widgets\ActiveForm;
// use kartik\builder\Form;
$this->title = 'Voting';
?>
<div class="vote-index">

    <div class="jumbotron">
        <h1><?= Yii::$app->name ?></h1>
        <p class="lead"><?=Yii::t('app', 'Please fill in your token in the form below to start voting!')?></p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-md-offset-4">
            <!--
            <h1 class="text-center token-title">Please fill in your code in the form below to start voting</h1>
            -->
                <div class="login-box clearfix">
                    <div class="col-lg-12">
                    <center style="margin: 15px 0;">
                        <span aria-hidden="true" class="glyphicon glyphicon-lock " style="font-size: 50px; color:lightgrey;"></span>
                    </center>
                    </div>
                    <?php
                    // glyphicon-tag or  glyphicon-lock  or glyphicon-log-in ?
                    //<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">
                    $form = ActiveForm::begin([
                        'id' => 'token-input-form',
                        'options' => ['class' => 'form-vertical'],
                        'fieldConfig' => [
                            'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                            //'labelOptions' => ['class' => 'col-lg-1 control-label'],
                        ],
                    ]);

                    echo  $form->field($model, 'token', [
                        //'options' => ['class' => 'autofocus'],
                        'inputOptions'=> [
                            'placeholder'=> Yii::t('app', 'Please enter your token'),
                        ],
                    //])->textInput()->hint('Please enter your token')->label('Token')
                    ])->textInput(['class' => 'form-control autofocus'])->label(false);

                    ?>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-block btn-success', 'name' => 'token-input-button']) ?>
                        </div>
                    </div>
                    <a href="<?=Url::to('site/contact')?>" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                    <?php ActiveForm::end(); ?>
                </div> <!--account-wall end -->
                <? if (Yii::$app->user->isGuest): ?>
                <a href="<?=Url::to('site/login')?>" class="text-center login-account">Login with an account?</a>
                <? endif;?>
            </div>
        </div>
    </div>
</div>
