<?php
use yii\helpers\Url;
use app\components\helpers\PollUrl;

echo $this->context->createUrl(['update','id'=>1]). '<br />';
echo $this->context->createUrl(['/poll/view']). '<br />';
echo Url::toRoute(['/poll/view', 'id'=>$this->context->getPollId()]).'<br />';
echo PollUrl::toRoute(['/poll/view']).'<br />';


echo PollUrl::toRoute(['/member/view','id'=>1]).'<br />';
echo PollUrl::toRoute(['/members/view','id'=>1]).'<br />';

echo PollUrl::toRoute(['/members/view','id'=>1, 'poll_id'=>2]).'<br />';
echo PollUrl::toRoute(['view','id'=>1]).'<br />';

