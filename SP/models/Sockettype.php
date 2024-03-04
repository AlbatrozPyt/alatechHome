<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sockettype".
 *
 * @property int $id
 * @property string $name
 *
 * @property Motherboard[] $motherboards
 * @property Processor[] $processors
 */
class Sockettype extends \yii\db\ActiveRecord
{

    public function fields()
    {
        return ['name'];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sockettype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 96],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Motherboards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotherboards()
    {
        return $this->hasMany(Motherboard::class, ['socketTypeId' => 'id']);
    }

    /**
     * Gets query for [[Processors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessors()
    {
        return $this->hasMany(Processor::class, ['socketTypeId' => 'id']);
    }
}
