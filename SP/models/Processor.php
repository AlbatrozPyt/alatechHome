<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "processor".
 *
 * @property int $id
 * @property string $name
 * @property string $imageUrl
 * @property int $brandId
 * @property int $socketTypeId
 * @property int $cores
 * @property float $baseFrequency
 * @property float $maxFrequency
 * @property float $cacheMemory
 * @property int $tdp
 *
 * @property Brand $brand
 * @property Machine[] $machines
 * @property Sockettype $socketType
 */
class Processor extends \yii\db\ActiveRecord
{

    public function fields()
    {
        return [
            'name',
            'imageUrl',
            'brand',
            'socketType',
            'cores',
            'baseFrequency',
            'maxFrequency',
            'cacheMemory',
            'tdp'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'processor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'imageUrl', 'brandId', 'socketTypeId', 'cores', 'baseFrequency', 'maxFrequency', 'cacheMemory', 'tdp'], 'required'],
            [['brandId', 'socketTypeId', 'cores', 'tdp'], 'integer'],
            [['baseFrequency', 'maxFrequency', 'cacheMemory'], 'number'],
            [['name'], 'string', 'max' => 96],
            [['imageUrl'], 'string', 'max' => 512],
            [['socketTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => Sockettype::class, 'targetAttribute' => ['socketTypeId' => 'id']],
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
            'socketTypeId' => 'Socket Type ID',
            'cores' => 'Cores',
            'baseFrequency' => 'Base Frequency',
            'maxFrequency' => 'Max Frequency',
            'cacheMemory' => 'Cache Memory',
            'tdp' => 'Tdp',
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
        return $this->hasMany(Machine::class, ['processorId' => 'id']);
    }

    /**
     * Gets query for [[SocketType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocketType()
    {
        return $this->hasOne(Sockettype::class, ['id' => 'socketTypeId']);
    }
}
