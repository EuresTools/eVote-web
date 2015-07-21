<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

?>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelContacts[0],
        'formId' => $form->id,
        'formFields' => [
            'name',
            'email',
        ],
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-list"></i> Contacts
                <button type="button" class="add-item btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Add</button>
            </h4>
        </div>
        <div class="panel-body">
            <div class="container-items"><!-- widgetBody -->
            <?php foreach ($modelContacts as $i => $contact): ?>
                <div class="item panel panel-default"><!-- widgetItem -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Contact</h3>
                        <div class="pull-right">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $contact->isNewRecord) {
                                echo Html::activeHiddenInput($contact, "[{$i}]id");
                            }
                        ?>
                    <div class="row">
                        <div class="col-sm-6">
                        <?= $form->field($contact, "[{$i}]email", ['options' => ['class' => 'form-group kv-fieldset-inline']])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-6">
                        <?= $form->field($contact, "[{$i}]name", ['options' => ['class' => 'form-group kv-fieldset-inline']])->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div><!-- .panel -->
    <?php DynamicFormWidget::end(); ?>
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_READY') ?>
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Are you sure you want to delete this item?")) {
        return false;
    }
    return true;
});
<?php $this->endBlock();
?>
</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);
