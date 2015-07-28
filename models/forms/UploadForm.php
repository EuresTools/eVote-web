<?php

namespace app\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    public $excelFile;

    public function rules() {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx', 'checkExtensionByMimeType' => true],
        ];
    }

    public function upload() {
        if($this->validate()) {
            return $this->excelFile;
        }
        return false;
    }
}
