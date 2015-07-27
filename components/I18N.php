<?php

namespace app\components;

class I18N extends \yii\i18n\I18N
{
    public function translate($category, $message, $params, $language)
    {
        if (($language=='en' || $language=='en-GB') && $category=='yii') {
            $language = 'en-US';
        }
        return parent::translate($category, $message, $params, $language);
    }
}
