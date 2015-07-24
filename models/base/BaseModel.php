<?php
namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\models\User;

class BaseModel extends ActiveRecord
{
    public static function representingColumn()
    {
        return null;
    }

    public static function representingColumnDivider()
    {
        return '-';
    }

    /**
     * The active record label.
     * The active record label is the user friendly name displayed in the views.
     * Each active record class should override this method and explicitly specify the label.
     * See the documentation when overriding: http://www.yiiframework.com/doc/guide/1.1/en/topics.i18n#plural-forms-format
     * @param integer $n The number value. This is used to support plurals. Defaults to 1 (means singular).
     * Notice that this number doesn't necessarily corresponds to the number (count) of items.
     * @return string The label.
     * @throws Exception If the method wasn't overriden.
     * @see getRelationLabel
     */
    public static function label($n = 1)
    {
        // echo \Yii::t('app', 'There {n, plural, =0{are no cats} =1{is one cat} other{are # cats}}!', ['n' => $n]);
        throw new \Exception('This method should be overriden by the Active Record class.');
    }

    /**
     * Returns a string representation of the model instance, based on
     * {@link representingColumn}.
     * If the representing column is not set, the primary key will be used.
     * If there is no primary key, the first field will be used.
     * When you overwrite this method, all model attributes used to build
     * the string representation of the model must be specified in
     * {@link representingColumn}.
     * @return string The string representation for the model instance.
     * @see representingColumn
     */
    public function __toString()
    {
        $representingColumn = $this->representingColumn();

        if (($representingColumn === null) || ($representingColumn === array())) {
            if ($this->getTableSchema()->primaryKey !== null) {
                $representingColumn = $this->getTableSchema()->primaryKey;
            } else {
                $columnNames = $this->getTableSchema()->getColumnNames();
                $representingColumn = $columnNames[0];
            }

        }

        /* // ueberlegung bei einem error noch den primary key auszugeben
        //if($error=Yii::app()->errorHandler->error)
            if(is_array($representingColumn))
                $representingColumn[] = $this->getTableSchema()->primaryKey;
            else
                $representingColumn.=$this->representingColumnDivider().$this->getTableSchema()->primaryKey;
        */

        if (is_array($representingColumn)) {
            $part = '';
            foreach ($representingColumn as $representingColumn_item) {
                $part .= ( \yii\helpers\ArrayHelper::getValue($this, $representingColumn_item) === null ? '' : \yii\helpers\ArrayHelper::getValue($this, $representingColumn_item)) . $this->representingColumnDivider();
                //$part .= ( $this->$representingColumn_item === null ? '' : $this->$representingColumn_item) . $this->representingColumn_divider();
            }
            return substr($part, 0, strlen($this->representingColumnDivider())*-1);
        } else {
            return \yii\helpers\ArrayHelper::getValue($this, $representingColumn) === null ? '' : (string) \yii\helpers\ArrayHelper::getValue($this, $representingColumn);
        }
    }


    public function transactions()
    {
        return [
        'default' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE,
        'api' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
                },
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // 'createdAtAttribute' => 'create_time',
                // 'updatedAtAttribute' => 'update_time',
                //'value' => new Expression('NOW()'),
                'value' => new Expression('UTC_TIMESTAMP()'),

            ],
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id'=> 'created_by']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEditor()
    {
        return $this->hasOne(User::className(), ['id'=> 'updated_by']);
    }

}
