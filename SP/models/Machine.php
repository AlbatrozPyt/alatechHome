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

    public function getStorageDeviceSata()
    {
        return $this->getStorageDevices()->andWhere(['StorageDeviceInterface' => 'sata']);
    }

    public function getStorageDeviceM2()
    {
        return $this->getStorageDevices()->andWhere(['StorageDeviceInterface' => 'm2']);
    }

    public function Verificar()
    {
        Yii::$app->response->statusCode = 422;

        if ( $this->motherboard->socketType->name !== $this->processor->socketType->name)
        {
            return ['SocketType' => 'SocketTypes diferentes.'];
        }
        else if ($this->processor->tdp > $this->motherboard->maxTdp) {
            return ['TDP' > 'TDP maior que o suportado.'];
        }
        else if ($this->motherboard->ramMemoryType->name !== $this->ramMemory->ramMemoryType->name) {
            return ['Ram Memory' > 'Ram memories diferentes.'];
        }
        else if ($this->ramMemoryAmount > $this->motherboard->ramMemorySlots) {
            return ['Ram Memory Amount' > 'Mais Ram memories do que o suportado.'];
        }
        else if ($this->graphicCardAmount > $this->motherboard->pciSlots) {
            return ['Graphic Cards Amount' > 'Mais Graphic Cards do que o suportado.'];
        }
        else if ($this->getStorageDeviceSata()->count() > $this->motherboard->sataSlots ) {
            return ['Sata Slots' > 'Mais dispositivos Sata do que o suportado.'];
        }
        else if ($this->getStorageDeviceM2()->count() > $this->motherboard->m2Slots){
            return ['M2 Slots' > 'Mais dispositivos Sata do que o suportado.'];
        }
        else if ($this->getStorageDeviceSata()->count() === 0 && $this->getStorageDeviceM2() === 0) {
            return ['Storage Devices' > 'Deve haver pelo menos 1 Storage Device.'];
        }
        else if ($this->graphicCardAmount > 1 && $this->graphicCard->supportMultiGpu === 0) {
            return ['Graphic Card' => 'A sua maquina não suporta mais de uma Graphic Card.'];
        }
        else if ($this->powerSupply->potency < $this->graphicCard->minimumPowerSupply*$this->graphicCardAmount) {
            return ['Power Supply' => 'A potência da Power Supply é menor do que a potência esperada.'];
        }

        Yii::$app->response->statusCode = 200;
        return ['message' => 'Máquina válida'];
    }
}
