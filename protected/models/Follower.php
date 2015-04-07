<?php

/**
 * This is the model class for table "followers".
 *
 * The followings are the available columns in table 'followers':
 * @property integer $id
 * @property integer $follower_id
 * @property integer $followee_id
 * @property integer $created
 *
 * The followings are the available model relations:
 * @property Users $followee
 * @property Users $follower
 */
class Follower extends CActiveRecord
{
	/**
	 * Adds the CTimestampBehavior to this class
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' 			=> 'zii.behaviors.CTimestampBehavior',
				'createAttribute' 	=> 'created',
				'updateAttribute' 	=> 'created',
				'setUpdateOnCreate' => true
			)
		);
	}

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'followers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('follower_id, followee_id, created', 'numerical', 'integerOnly'=>true),
            array('id, follower_id, followee_id, created', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'follower_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'follower_id' => 'Follower',
            'followee_id' => 'Followee',
            'created' => 'Created',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('follower_id',$this->follower_id);
        $criteria->compare('followee_id',$this->followee_id);
        $criteria->compare('created',$this->created);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Followers the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
