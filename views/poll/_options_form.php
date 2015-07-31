<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\models\Option;

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
                <i class="glyphicon glyphicon-list"></i> <?= Option::label(2)?>
                <button type="button" data-action="add-item" class="add-item btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Add</button>
            </h4>
        </div>
        <div class="panel-body">
            <div class="container-items container-fluid"><!-- widgetBody -->
<?php
foreach ($modelOptions as $i => $option) {
    echo Html::beginTag('div', ['class'=>'item']);
    if (!$option->isNewRecord) {
        echo Html::activeHiddenInput($option, "[{$i}]id");
    }

    echo $form->field($option, "[{$i}]text", [
        'addon' => [
            'append' => [
                'content' => Html::a('<i class="glyphicon glyphicon-minus"></i>', '#', ['class'=>'remove-item btn btn-danger', 'data-action'=>'remove-item']),
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
    echo Html::endTag('div', ['class'=>'item']);
}
?>
            </div><!-- .container-items -->
        </div><!-- .panel-body -->
    </div><!-- .panel -->
    <?php DynamicFormWidget::end(); ?>
<script type="text/javascript">
<?php $this->beginBlock('JS_READY') ?>
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Are you sure you want to delete this item?")) {
        return false;
    }
    return true;
});
/*
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    var ok, cancel;
    yii.confirm("Are you sure you want to delete this item?", ok, cancel);
    if (ok) {
        return true;
    }
    console.log('cancel');
    return false;
});
*/
<?php $this->endBlock();
?>
</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);
