<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use app\models\Poll;
use app\components\controllers\VotingRestController;

class PollController extends VotingRestController
{
    public $modelClass = 'app\models\Poll';


}
