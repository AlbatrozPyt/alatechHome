<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motherboard".
 *
 * @property int $id
 * @property string $name
 * @property string $imageUrl
 * @property int $brandId
 * @property int $socketTypeId
 * @property int $ramMemoryTypeId
 * @property int $ramMemorySlots
 * @property int $maxTdp
 * @property int $sataSlots
 * @property int $m2Slots
 * @property int $pciSlots
 *
 * @property Brand $brand
 * @property Machine[] $machines
 * @property Rammemorytype $ramMemoryType
 * @property Sockettype $socketType
 */
class Motherboard extends \yii\db\ActiveRecord
{


    public function fields()
    {
        return [
            'name',
            'imageUrl',
            'brand',
            'socketType',
            'ramMemoryType',
            'ramMemorySlots',
            'maxTdp',
            'sataSlots',
            'm2Slots',
            'pciSlots',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motherboard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'imageUrl', 'brandId', 'socketTypeId', 'ramMemoryTypeId', 'ramMemorySlots', 'maxTdp', 'sataSlots', 'm2Slots', 'pciSlots'], 'required'],
            [['brandId', 'socketTypeId', 'ramMemoryTypeId', 'ramMemorySlots', 'maxTdp', 'sataSlots', 'm2Slots', 'pciSlots'], 'integer'],
            [['name'], 'string', 'max' => 96],
            [['imageUrl'], 'string', 'max' => 512],
            [['ramMemoryTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => Rammemorytype::class, 'targetAttribute' => ['ramMemoryTypeId' => 'id']],
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
            'ramMemoryTypeId' => 'Ram Memory Type ID',
            'ramMemorySlots' => 'Ram Memory Slots',
            'maxTdp' => 'Max Tdp',
            'sataSlots' => 'Sata Slots',
            'm2Slots' => 'M2slots',
            'pciSlots' => 'Pci Slots',
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
        return $this->hasMany(Machine::class, ['motherboardId' => 'id']);
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
