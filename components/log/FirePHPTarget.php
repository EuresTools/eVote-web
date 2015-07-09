<?php
/**
 * @link https://github.com/pgrzelka/yii2-firephp
 * @author Piotr Grzelka
 * fixed code in collect so that no additional application message is added
 * @license MIT
 */

namespace app\components\log;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\log\Logger;
use yii\log\Target;

/**
 *
 */
class FirePHPTarget extends Target
{

    /**
     * Initializes the route.
     * This method is invoked after the route is created by the route manager.
     */
    public function init()
    {
        parent::init();
    }


    /**
     * Processes the given log messages.
     * This method will filter the given messages with [[levels]] and [[categories]].
     * And if requested, it will also export the filtering result to specific medium (e.g. email).
     * @param array $messages log messages to be processed. See [[Logger::messages]] for the structure
     * of each message.
     * @param boolean $final whether this method is called at the end of the current application
     */
    public function collect($messages, $final)
    {
        $this->messages = array_merge($this->messages, $this->filterMessages($messages, $this->getLevels(), $this->categories, $this->except));
        $count = count($this->messages);

        if ($count > 0 && ($final || $this->exportInterval > 0 && $count >= $this->exportInterval)) {
            // removed from the src of Target otherwise i always get an additional message log
            // if (($context = $this->getContextMessage()) !== '') {
            //     $this->messages[] = [$context, Logger::LEVEL_INFO, 'application', YII_BEGIN_TIME];
            // }
            // set exportInterval to 0 to avoid triggering export again while exporting
            $oldExportInterval = $this->exportInterval;
            $this->exportInterval = 0;
            $this->export();
            $this->exportInterval = $oldExportInterval;

            $this->messages = [];
        }
    }

    /**
     * Writes log messages to FirePHP.
     */
    public function export()
    {
        $firephp = \FirePHP::getInstance(true);

        try {
            foreach ($this->messages as $message) {
                if (true) {
                    switch ($message[1]) {
                        case Logger::LEVEL_ERROR:
                            $firephp->error($message[0], $message[2]);
                            break;
                        case Logger::LEVEL_WARNING:
                            $firephp->warn($message[0], $message[2]);
                            break;
                        case Logger::LEVEL_INFO:
                            $firephp->info($message[0], $message[2]);
                            break;
                        case Logger::LEVEL_TRACE:
                            $firephp->log($message[0], $message[2]);
                            break;
                        default:
                            $firephp->log($message[0], $message[2]);
                            break;
    //                    case Logger::LEVEL_PROFILE:
    //                        $firephp->log($message[0], $message[2]);
    //                        break;
    //                    case Logger::LEVEL_PROFILE_BEGIN:
    //                        $firephp->log($message[0], $message[2]);
    //                        break;
    //                    case Logger::LEVEL_PROFILE_END:
    //                        $firephp->log($message[0], $message[2]);
    //                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}
