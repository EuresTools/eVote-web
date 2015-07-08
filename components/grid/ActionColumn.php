<?php

/**
 * @copyright Copyright &copy; Hoft Benjamin, 2014
 * @package yii2-grid
 * @version 2.1.0
 */

namespace app\components\grid;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Extends the Kartics's ActionColumn for the Grid widget [[\kartik\widgets\GridView]]
 * with various settings.
 *
 * ActionColumn is a column for the [[GridView]] widget that displays buttons
 * for viewing and manipulating the items.
 *
 * @author original author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class ActionColumn extends \kartik\grid\ActionColumn
{
    /**
     * @var boolean whether to merge the header title row and the filter row
     * This will not render the filter for the column and can be used when `filter`
     * is set to `false`. Defaults to `false`. This is only applicable when `filterPosition`
     * for the grid is set to FILTER_POS_BODY.
     */
    public $mergeHeader = false;

    public $header=null;
    public $contentOptions = ['class'=>'skip-export buttonColumn'];

    public function init()
    {
        parent::init();
        //$this->header = '';
        //$this->contentOptions = ArrayHelper::merge(['class'=>'buttonColumn', ], $this->contentOptions);
    }
}
