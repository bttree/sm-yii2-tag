<?php

namespace bttree\smytag\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag".
 *
 * @property integer      $id
 * @property string       $model_class
 * @property integer      $model_id
 * @property string       $title
 *
 * @property ActiveRecord $model
 */
class Tag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_class', 'model_id', 'title'], 'required'],
            [['model_id'], 'integer'],
            ['title', 'filter', 'filter' => 'mb_strtolower'],
            [['model_class', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('smy.tag', 'ID'),
            'model_class' => Yii::t('smy.tag', 'Model Class'),
            'model_id'    => Yii::t('smy.tag', 'Model ID'),
            'title'       => Yii::t('smy.tag', 'Tag'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne($this->model_class, ['id' => 'model_id']);
    }
}
