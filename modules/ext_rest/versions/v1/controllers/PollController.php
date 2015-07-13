<?php

namespace app\modules\ext_rest\versions\v1\controllers;

use Yii;
use app\models\Poll;
use app\components\controllers\BaseRestController;

class PollController extends BaseRestController
{
    public $modelClass = 'app\models\Poll';


}
