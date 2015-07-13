<?php

namespace app\modules\ext_rest\versions\v2\controllers;

use Yii;
use app\models\Poll;
use app\components\controllers\VotingRestController;

class PollController extends VotingRestController
{
    public $modelClass = 'app\models\Poll';


}
