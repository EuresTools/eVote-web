<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
?>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'min' => 2, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelOptions[0],
        'formId' => $form->id,
        'formFields' => [
            'text',
        ],
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-list"></i> Options
                <button type="button" class="add-item btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Add</button>
            </h4>
        </div>
        <div class="panel-body">
            <div class="container-items container-fluid"><!-- widgetBody -->
            <?php foreach ($modelOptions as $i => $option): ?>
                <div class="item"><!-- widgetItem -->
                    <?php
                        // necessary for update action.
                        if (! $option->isNewRecord) {
                            echo Html::activeHiddenInput($option, "[{$i}]id");
                        }
                    ?>
                    <?php
                        echo $form->field($option, "[{$i}]text", [
                                'addon' => [
                                    'append' => [
                                        'content' => Html::button('<i class="glyphicon glyphicon-minus"></i>', ['class'=>'remove-item btn btn-danger']),
                                        'asButton' => true,
                                    ],
                                ],
                                'inputOptions'=> [
                                    'placeholder'=> Yii::t('app', 'Please fill with an option text'),
                                ],
                                // 'labelOptions'=> [
                                //     'label' => 'Option',
                                // ],
                                'options' => [
                                    'class' =>'form-group kv-fieldset-inline'
                                ],
                        ])->textInput(['maxlength' => true]);
                    ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div><!-- .panel -->
    <?php DynamicFormWidget::end(); ?>

</div>
