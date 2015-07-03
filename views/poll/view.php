<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\MemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Poll */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Polls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

<?php
    // Populate the attribute array for display.
    $attributes = ['question:ntext'];
    foreach ($model->getOptions()->all() as $index => $option) {
        $no = $index + 1;
        $attributes[] = ['attribute' => "Option $no", 'value' => $option->text];
    }

?>

    <h2>Poll</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>


    <h2>About</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'question:ntext',
            'title',
            [
                'attribute' => 'organizer_id',
                'format' => 'raw',
                'value' => Yii::$app->user->identity->isAdmin() ? Html::a(Html::encode($model->getOrganizer()->one()->name), ['organizer/view', 'id' => $model->getOrganizer()->one()->id]) : Html::encode($model->getOrganizer()->one()->name),
            ],
            'select_min',
            'select_max',
            'start_time',
            'end_time',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php
        $members = $model->getMembers()->all();
        //$dataProvider = new ArrayDataProvider([
            //'allModels' => $members,
        //]);

        if(count($members) >= 0) {
            echo Html::tag('h2', 'Members');
            echo Html::button('Edit Members', ['class' => 'btn btn-primary']);
            echo GridView::widget([
                'dataProvider' => $memberDataProvider,
                'filterModel' => $memberSearchModel, 
                //'itemView' => function($model, $key, $index, $widget) {
                    //return $model->name;
                'columns' => [
                    'name',
                ],
            ]);
        }
    ?>

</div>

