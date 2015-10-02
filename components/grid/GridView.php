<?php

/**
 * @copyright Copyright &copy; Hoft Benjamin, 2014
 * @package yii2-grid
 * @version 2.0.0
 */

namespace app\components\grid;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\icons\Icon;

/**
 * Enhances the Kartik GridView widget with various options.
 *
 * @author Hoft Benjamin <hoft@eurescom.eu>
 * @since 1.0
 */
class GridView extends \kartik\grid\GridView
{
    /**
     * @var boolean whether the grid view will be rendered within a pjax container.
     * Defaults to `true`. If set to `true`, the entire GridView widget will be parsed
     * via Pjax and auto-rendered inside a yii\widgets\Pjax widget container. If set to
     * `false` pjax will be disabled and none of the pjax settings will be applied.
     */
    public $pjax = true;

    /**
     * @var boolean whether the grid table will highlight row on `hover`.
     * Applicable only if `bootstrap` is `true`. Defaults to `true`.
     */
    public $hover = true;

    /**
     * @var boolean whether the grid table will have a floating table header.
     * Defaults to `true`.
     */
    public $floatHeader = false;

    /**
     * @var boolean whether to show the page summary row for the table. This will
     * be displayed above the footer.
     */
    public $showPageSummary = false;

     /**
     * @var boolean whether the grid table will have a `condensed` style.
     * Applicable only if `bootstrap` is `true`. Defaults to `false`.
     */
    public $condensed = false;

    /*
    enable responsive mode for mobile devices
     */
    public $responsive = true;

    public $responsiveWrap = false;

    /**
    * @inheritdoc
    */
    public $resizableColumns = false;

    /**
    * @inheritdoc
    */
    public $persistResize = false;


    /**
    * @inheritdoc
    */
    public $pjaxSettings = ['loadingCssClass'=>'grid-loading'];

    /**
    * @inheritdoc
    */
    //public $exportConfig = ['label'=>'Export'];

    /**
    * @inheritdoc
    */
    public $toggleDataOptions = [
            'all' => [
                'icon' => 'resize-full',
                'label' => 'All',
                'class' => 'btn btn-default',
                'title' => 'Show all data'
            ],
            'page' => [
                'icon' => 'resize-small',
                'label' => 'Page',
                'class' => 'btn btn-default',
                'title' => 'Show first page data'
            ],
        ];

    public function init()
    {
        if ($this->panel) {
            $this->bordered=false;
            $this->condensed == true;
            $this->presetPanelIcon();
        }

        if ($this->resizableColumns && $this->persistResize) {
            // set the default resizeStorageKey
            $this->resizeStorageKey = Yii::$app->user->id . '-' . date("m");
        }


        // enable first and last pager button
        $this->pager = [
            // 'firstPageLabel'=> Icon::show('fast-backward', ['class' => ''], Icon::BSG),
            // 'lastPageLabel'=> Icon::show('fast-forward', ['class' => ''], Icon::BSG),
            // 'prevPageLabel'=> Icon::show('chevron-left', ['class' => ''], Icon::BSG),
            // 'nextPageLabel'=> Icon::show('chevron-right', ['class' => ''], Icon::BSG),

            //'firstPageLabel'=>'&laquo;',
            //'lastPageLabel'=>'&raquo;',

            'prevPageLabel'=>'&lt;',
            'nextPageLabel'=>'&gt;',
            'firstPageLabel'=>'<< First',
            'lastPageLabel'=>'Last >>',
        ];

        /*
        $this->toolbar = [
            ['content'=>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>Yii::t('kvgrid', 'Reset Grid')])
            ],
            '{export}',
            '{toggleData}',
        ];
        */





        parent::init();

        $this->export = ArrayHelper::merge([
                'label' => Yii::t('kvgrid', 'Export'),
                'icon' => 'export',
                'browserPopupsMsg' => Yii::t('kvgrid', 'Disable any popup blockers in your browser to ensure proper download.'),
                'options' => ['class' => 'btn btn-danger']
            ], $this->export);
    }

    public function presetPanelIcon()
    {
        $heading_icon = ArrayHelper::getValue($this->panel, 'heading-icon', 'th-list');
        $heading_icon_options = ArrayHelper::getValue($this->panel, 'heading-icon-options', ['class' => '']);
        $heading_icon_type = ArrayHelper::getValue($this->panel, 'heading-icon-type', Icon::BSG);

        $this->panel['heading'] = Icon::show($heading_icon, $heading_icon_options, $heading_icon_type) . $this->panel['heading'];

    }
}
