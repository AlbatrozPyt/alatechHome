<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "machine".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $imageUrl
 * @property int $motherboardId
 * @property int $processorId
 * @property int $ramMemoryId
 * @property int $ramMemoryAmount
 * @property int $graphicCardId
 * @property int $graphicCardAmount
 * @property int $powerSupplyId
 *
 * @property Graphiccard $graphicCard
 * @property Machinehasstoragedevice[] $machinehasstoragedevices
 * @property Motherboard $motherboard
 * @property Powersupply $powerSupply
 * @property Processor $processor
 * @property Rammemory $ramMemory
 * @property Storagedevice[] $storageDevices
 */
class Machine extends \yii\db\ActiveRecord
{

    public function fields()
    {
        return [
            'name',
            'description',
            'imageUrl',
            'motherboard',
            'processor',
            'ramMemory',
            'ramMemoryAmount',
            'graphicCard',
            'graphicCardAmount',
            'powerSupply',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'machine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'imageUrl', 'motherboardId', 'processorId', 'ramMemoryId', 'ramMemoryAmount', 'graphicCardId', 'graphicCardAmount', 'powerSupplyId'], 'required'],
            [['motherboardId', 'processorId', 'ramMemoryId', 'ramMemoryAmount', 'graphicCardId', 'graphicCardAmount', 'powerSupplyId'], 'integer'],
            [['name'], 'string', 'max' => 96],
            [['description', 'imageUrl'], 'string', 'max' => 512],
            [['motherboardId'], 'exist', 'skipOnError' => true, 'targetClass' => Motherboard::class, 'targetAttribute' => ['motherboardId' => 'id']],
            [['processorId'], 'exist', 'skipOnError' => true, 'targetClass' => Processor::class, 'targetAttribute' => ['processorId' => 'id']],
            [['ramMemoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Rammemory::class, 'targetAttribute' => ['ramMemoryId' => 'id']],
            [['graphicCardId'], 'exist', 'skipOnError' => true, 'targetClass' => Graphiccard::class, 'targetAttribute' => ['graphicCardId' => 'id']],
            [['powerSupplyId'], 'exist', 'skipOnError' => true, 'targetClass' => Powersupply::class, 'targetAttribute' => ['powerSupplyId' => 'id']],
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
            'description' => 'Description',
            'imageUrl' => 'Image Url',
            'motherboardId' => 'Motherboard ID',
            'processorId' => 'Processor ID',
            'ramMemoryId' => 'Ram Memory ID',
            'ramMemoryAmount' => 'Ram Memory Amount',
            'graphicCardId' => 'Graphic Card ID',
            'graphicCardAmount' => 'Graphic Card Amount',
            'powerSupplyId' => 'Power Supply ID',
        ];
    }

    /**
     * Gets query for [[GraphicCard]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraphicCard()
    {
        return $this->hasOne(Graphiccard::class, ['id' => 'graphicCardId']);
    }

    /**
     * Gets query for [[Machinehasstoragedevices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMachinehasstoragedevices()
    {
        return $this->hasMany(Machinehasstoragedevice::class, ['machineId' => 'id']);
    }

    /**
     * Gets query for [[Motherboard]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotherboard()
    {
        return $this->hasOne(Motherboard::class, ['id' => 'motherboardId']);
    }

    /**
     * Gets query for [[PowerSupply]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPowerSupply()
    {
        return $this->hasOne(Powersupply::class, ['id' => 'powerSupplyId']);
    }

    /**
     * Gets query for [[Processor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessor()
    {
        return $this->hasOne(Processor::class, ['id' => 'processorId']);
    }

    /**
     * Gets query for [[RamMemory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRamMemory()
    {
        return $this->hasOne(Rammemory::class, ['id' => 'ramMemoryId']);
    }

    /**
     * Gets query for [[StorageDevices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStorageDevices()
    {
        return $this->hasMany(Storagedevice::class, ['id' => 'storageDeviceId'])->viaTable('machinehasstoragedevice', ['machineId' => 'id']);
    }
}
