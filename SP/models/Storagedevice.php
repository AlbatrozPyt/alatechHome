<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "storagedevice".
 *
 * @property int $id
 * @property string $name
 * @property string $imageUrl
 * @property int $brandId
 * @property string $storageDeviceType
 * @property int $size
 * @property string $storageDeviceInterface
 *
 * @property Brand $brand
 * @property Machinehasstoragedevice[] $machinehasstoragedevices
 * @property Machine[] $machines
 */
class Storagedevice extends \yii\db\ActiveRecord
{

    public function fields()
    {
        return [
            'name',
            'imageUrl',
            'brand',
            'storageDeviceType',
            'size',
            'storageDeviceInterface'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'storagedevice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'imageUrl', 'brandId', 'storageDeviceType', 'size', 'storageDeviceInterface'], 'required'],
            [['brandId', 'size'], 'integer'],
            [['storageDeviceType', 'storageDeviceInterface'], 'string'],
            [['name'], 'string', 'max' => 96],
            [['imageUrl'], 'string', 'max' => 512],
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
            'storageDeviceType' => 'Storage Device Type',
            'size' => 'Size',
            'storageDeviceInterface' => 'Storage Device Interface',
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
     * Gets query for [[Machinehasstoragedevices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMachinehasstoragedevices()
    {
        return $this->hasMany(Machinehasstoragedevice::class, ['storageDeviceId' => 'id']);
    }

    /**
     * Gets query for [[Machines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMachines()
    {
        return $this->hasMany(Machine::class, ['id' => 'machineId'])->viaTable('machinehasstoragedevice', ['storageDeviceId' => 'id']);
    }
}
