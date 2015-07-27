<?php
/*
author :: Pitt Phunsanit
website :: http://plusmagi.com
change language by get language=EN, language=TH,...
or select on this widget
*/

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Widget;
use yii\bootstrap\Nav;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Dropdown;
use yii\helpers\Url;
use yii\web\Cookie;

class languageSwitcher extends Nav
{
    public $type;

    public $languages = [
        'en-GB' => 'English',
        //'en-US'=>'American English',
        'de-DE' => 'German',
        'fr' => 'French',
//        'th' => 'Thai',
    ];

    public function init()
    {
        if (php_sapi_name() === 'cli') {
            return true;
        }

        parent::init();

        $cookies = Yii::$app->request->cookies;

        $languageNew = Yii::$app->request->get('language');
        if ($languageNew) {
            $this->setNewLanguage($languageNew);
        } elseif ($cookies->has('language')) {
            $this->setNewLanguage($cookies->getValue('language'));
        }
    }

    protected function setNewLanguage($languageNew)
    {
        if (isset($this->languages[$languageNew])) {
            if ($languageNew != Yii::$app->sourceLanguage) {
                Yii::$app->language = $languageNew;
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => $languageNew
                ]));
            } else {
                Yii::$app->language = Yii::$app->sourceLanguage;
            }
        }
    }

    public function run()
    {
        $languages = $this->languages;
        //print_pre(Yii::$app->language,'default language');

        $current = $languages[Yii::$app->language];
        unset($languages[Yii::$app->language]);

        $items = [];
        foreach ($languages as $code => $language) {
            $temp = [];
            $temp['label'] = $language;
            $temp['url'] = Url::current(['language' => $code]);
            array_push($items, $temp);
        }

        $this->items[] = [
            'label' => $current,
            'items' => $items,
        ];
        echo parent::run();
    }

    /* // old function when the class was extending from Widget and not from Nav
    public function run()
    {
        $languages = $this->languages;
        $current = $languages[Yii::$app->language];
        unset($languages[Yii::$app->language]);

        $items = [];
        foreach ($languages as $code => $language) {
            $temp = [];
            $temp['label'] = $language;
            $temp['url'] = Url::current(['language' => $code]);
            array_push($items, $temp);
        }

        echo ButtonDropdown::widget([
            'label' => $current,
            'dropdown' => [
                'items' => $items,
            ],
        ]);
    }
    */
}
