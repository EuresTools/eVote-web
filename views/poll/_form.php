<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Poll */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="poll-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

    <?= $form->field($poll, 'question')->textarea(['rows' => 6]) ?>

    <?= $form->field($poll, 'select_min')->textInput() ?>

    <?= $form->field($poll, 'select_max')->textInput() ?>


    <?= $form->field($poll, 'start_time')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATETIME,
        'displayFormat' => 'd MMM, yyyy HH:mm',
        'saveFormat' => 'php:Y-m-d H:i:s',
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ],
    ]) ?>

    <?= $form->field($poll, 'end_time')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATETIME,
        'displayFormat' => 'd MMM, yyyy HH:mm',
        'saveFormat' => 'php:Y-m-d H:i:s',
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ],
    ]) ?>
    

    <?php //echo $form->field($model, 'organizer_id')->textInput() ?>

    <?php //echo $form->field($model, 'created_at')->textInput() ?>

    <?php //echo $form->field($model, 'updated_at')->textInput() ?>

    <div id='options-container'>
        <?php
        foreach ($options as $index => $option) {
            $no = $index + 1;
            echo $form->field($option, "[$index]text")->label("Option $no")->textInput();
        }
        ?>
    </div>
    <span class="input-group-btn">
        <button type="button" id="add-btn" class="btn btn-primary pull-right" onclick="addField()">
            <span class="glyphicon glyphicon-plus"></span>
        </button>
        <button type="button" id="remove-btn" class="btn btn-danger pull-right" onclick="removeField()">
            <span class="glyphicon glyphicon-minus"></span>
        </button>
    </span>

    <div class="form-group">
        <?= Html::submitButton($poll->isNewRecord ? 'Create' : 'Update', ['class' => $poll->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type='text/javascript'>

window.onload = function() {
    updateRemoveButton();
}

function updateRemoveButton() {
    var count = $("#options-container > div").length;
    if (count <= 2) {
        $("#remove-btn").hide();
    } else {
        $("#remove-btn").show();
    }
}

function addField() {
    var container = document.getElementById("options-container");
    var count = $("#options-container > div").length + 1;
    var index = count - 1;

    var div = document.createElement("div");
    div.className = "form-group field-option-" + index + "-text required";

    var label = document.createElement("label");
    label.className = "control-label";
    label.htmlFor = "option-" + index + "-text";
    label.innerHTML = "Option " + count;

    var input = document.createElement("input");
    input.type = "text";
    input.id = "option-" + index + "-text";
    input.className = "form-control";
    input.name = "Option[" + index + "][text]";

    var helpdiv = document.createElement("div");
    helpdiv.className = "help-block";

    div.appendChild(label);
    div.appendChild(input);
    div.appendChild(helpdiv);
    container.appendChild(div);

    updateRemoveButton();
}

function removeField() {
    var count = $("#options-container > div").length;
    if (count > 2) {
        var container = document.getElementById("options-container");
        var lastChild = container.lastChild;
        container.removeChild(lastChild);
    }
    updateRemoveButton();
}

</script>


