<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


// use kartik\widgets\ActiveForm;
// use kartik\builder\Form;

$this->title = 'E-Vote Start';
?>
<div class="vote-index">

    <div class="jumbotron">
        <h1>E-Vote</h1>
        <p class="lead">Please fill in your code in the form below to start voting!</p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-md-offset-4">
            <!--
            <h1 class="text-center token-title">Please fill in your code in the form below to start voting</h1>
            -->
                <div class="account-wall">

                    <div class="col-lg-12">
                    <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">

                    </div>

                        <!--
                        <span class="glyphicon glyphicon-log-in"></span>
                        -->
                    <?php
                        /*
                        $form = ActiveForm::begin([
                            'id' => 'token-input-form',
                            'type'=>ActiveForm::TYPE_VERTICAL
                        ]);


                        echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' => 1,
                        'attributes' => [
                    'token'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> Yii::t('app', 'Please enter your token'), 'maxlength'=>255]],
                            ]
                        ]);

                        */

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
                    <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                    <?php ActiveForm::end(); ?>
                </div> <!--account-wall end -->
                <a href="#" class="text-center login-account">Login with an account?</a>
            </div>
        </div>
    </div>
</div>
