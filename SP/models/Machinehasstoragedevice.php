<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "machinehasstoragedevice".
 *
 * @property int $machineId
 * @property int $storageDeviceId
 * @property int $amount
 *
 * @property Machine $machine
 * @property Storagedevice $storageDevice
 */
class Machinehasstoragedevice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'machinehasstoragedevice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machineId', 'storageDeviceId', 'amount'], 'required'],
            [['machineId', 'storageDeviceId', 'amount'], 'integer'],
            [['machineId', 'storageDeviceId'], 'unique', 'targetAttribute' => ['machineId', 'storageDeviceId']],
            [['machineId'], 'exist', 'skipOnError' => true, 'targetClass' => Machine::class, 'targetAttribute' => ['machineId' => 'id']],
            [['storageDeviceId'], 'exist', 'skipOnError' => true, 'targetClass' => Storagedevice::class, 'targetAttribute' => ['storageDeviceId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'machineId' => 'Machine ID',
            'storageDeviceId' => 'Storage Device ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * Gets query for [[Machine]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMachine()
    {
        return $this->hasOne(Machine::class, ['id' => 'machineId']);
    }

    /**
     * Gets query for [[StorageDevice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStorageDevice()
    {
        return $this->hasOne(Storagedevice::class, ['id' => 'storageDeviceId']);
    }
}
