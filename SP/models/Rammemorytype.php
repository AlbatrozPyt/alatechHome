<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rammemorytype".
 *
 * @property int $id
 * @property string $name
 *
 * @property Motherboard[] $motherboards
 * @property Rammemory[] $rammemories
 */
class Rammemorytype extends \yii\db\ActiveRecord
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
        return 'rammemorytype';
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
        return $this->hasMany(Motherboard::class, ['ramMemoryTypeId' => 'id']);
    }

    /**
     * Gets query for [[Rammemories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRammemories()
    {
        return $this->hasMany(Rammemory::class, ['ramMemoryTypeId' => 'id']);
    }
}
