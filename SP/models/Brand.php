<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name
 *
 * @property Graphiccard[] $graphiccards
 * @property Motherboard[] $motherboards
 * @property Powersupply[] $powersupplies
 * @property Processor[] $processors
 * @property Rammemory[] $rammemories
 * @property Storagedevice[] $storagedevices
 */
class Brand extends \yii\db\ActiveRecord
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
        return 'brand';
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
     * Gets query for [[Graphiccards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraphiccards()
    {
        return $this->hasMany(Graphiccard::class, ['brandId' => 'id']);
    }

    /**
     * Gets query for [[Motherboards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotherboards()
    {
        return $this->hasMany(Motherboard::class, ['brandId' => 'id']);
    }

    /**
     * Gets query for [[Powersupplies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPowersupplies()
    {
        return $this->hasMany(Powersupply::class, ['brandId' => 'id']);
    }

    /**
     * Gets query for [[Processors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessors()
    {
        return $this->hasMany(Processor::class, ['brandId' => 'id']);
    }

    /**
     * Gets query for [[Rammemories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRammemories()
    {
        return $this->hasMany(Rammemory::class, ['brandId' => 'id']);
    }

    /**
     * Gets query for [[Storagedevices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoragedevices()
    {
        return $this->hasMany(Storagedevice::class, ['brandId' => 'id']);
    }
}
