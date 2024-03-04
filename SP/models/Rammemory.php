<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rammemory".
 *
 * @property int $id
 * @property string $name
 * @property string $imageUrl
 * @property int $brandId
 * @property int $size
 * @property int $ramMemoryTypeId
 * @property float $frequency
 *
 * @property Brand $brand
 * @property Machine[] $machines
 * @property Rammemorytype $ramMemoryType
 */
class Rammemory extends \yii\db\ActiveRecord
{

    public function fields()
    {
        return [
            'name',
            'imageUrl',
            'brand',
            'size',
            'ramMemoryType',
            'frequency',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rammemory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'imageUrl', 'brandId', 'size', 'ramMemoryTypeId', 'frequency'], 'required'],
            [['brandId', 'size', 'ramMemoryTypeId'], 'integer'],
            [['frequency'], 'number'],
            [['name'], 'string', 'max' => 96],
            [['imageUrl'], 'string', 'max' => 512],
            [['ramMemoryTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => Rammemorytype::class, 'targetAttribute' => ['ramMemoryTypeId' => 'id']],
            [['brandId'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::class, 'targetAttribute' => ['brandId' => 'id']],
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
            'imageUrl' => 'Image Url',
            'brandId' => 'Brand ID',
            'size' => 'Size',
            'ramMemoryTypeId' => 'Ram Memory Type ID',
            'frequency' => 'Frequency',
        ];
    }

    /**
     * Gets query for [[Brand]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::class, ['id' => 'brandId']);
    }

    /**
     * Gets query for [[Machines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMachines()
    {
        return $this->hasMany(Machine::class, ['ramMemoryId' => 'id']);
    }

    /**
     * Gets query for [[RamMemoryType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRamMemoryType()
    {
        return $this->hasOne(Rammemorytype::class, ['id' => 'ramMemoryTypeId']);
    }
}
