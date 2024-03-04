<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "powersupply".
 *
 * @property int $id
 * @property string $name
 * @property string $imageUrl
 * @property int $brandId
 * @property int $potency
 * @property string $badge80Plus
 *
 * @property Brand $brand
 * @property Machine[] $machines
 */
class Powersupply extends \yii\db\ActiveRecord
{

    public function fields()
    {
        return [
            'name',
            'imageUrl',
            'brand' ,
            'potency',
            'badge80Plus',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'powersupply';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'imageUrl', 'brandId', 'potency', 'badge80Plus'], 'required'],
            [['brandId', 'potency'], 'integer'],
            [['badge80Plus'], 'string'],
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
            'potency' => 'Potency',
            'badge80Plus' => 'Badge80plus',
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
        return $this->hasMany(Machine::class, ['powerSupplyId' => 'id']);
    }
}
