<?php

/**
 * This is the model class for table "shares".
 *
 * The followings are the available columns in table 'shares':
 * @property integer $id
 * @property string $text
 * @property integer $author_id
 * @property integer $reply_id
 * @property integer $created
 *
 * The followings are the available model relations:
 * @property Users $author
 */
class Share extends CActiveRecord
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
        return 'shares';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text', 'required'),
			array('text', 'length', 'max' => 150),
            array('author_id, reply_id, created', 'numerical', 'integerOnly'=>true),
            array('id, text, author_id, reply_id, created', 'safe', 'on'=>'search'),
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
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'text' => 'Text',
            'author_id' => 'Author',
            'reply_id' => 'Reply',
            'created' => 'Created',
        );
    }

    /**
     * Notify everyone that was @mentioned in this share by email that they were mentioned
     */
    public function afterSave()
    {
        preg_match_all('/@([A-Za-z0-9\/\.]*)/', $this->text, $matches);
        $mentions = implode(',', $matches[1]);

        if (!empty($matches[1]))
        {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('username', $matches[1]);
            $users = User::model()->findAll($criteria);

            foreach ($users as $user)
            {
                $sendgrid = new SendGrid(Yii::app()->params['includes']['sendgrid']['username'], Yii::app()->params['includes']['sendgrid']['password']);
                $email    = new SendGrid\Email();

                $email->setFrom(Yii::app()->params['includes']['sendgrid']['from'])
                    ->addTo($user->email)
                    ->setSubject("You've Been @mentioned!")
                    ->setText("You've Been @mentioned!")
                    ->setHtml(Yii::app()->controller->renderPartial('//email/mention', array('share' => $this, 'user' => $user), true));

                // Send the email
                $sendgrid->send($email);
            }
        }

        return parent::afterSave();
    }

    /**
     * Likes a share, if it is not already liked
     * @return boolean
     */
    public function like()
    {
        $like = Like::model()->findByAttributes(array(
            'user_id' => Yii::app()->user->id,
            'share_id' => $this->id
        ));

        // Share is already liked, return true
        if ($like != NULL)
            return true;

        $like = new Like;
        $like->attributes = array(
            'share_id' => $this->id,
            'user_id' => Yii::app()->user->id
        );

        // Save the like
        return $like->save();
    }

    /**
     * Unlikes a share if it is liked
     * @return boolean
     */
    public function unlike()
    {
        $like = Like::model()->findByAttributes(array(
            'user_id' => Yii::app()->user->id,
            'share_id' => $this->id
        ));

        // Item is not already liked, return true
        if ($like == NULL)
            return true;

        // Delete the Like
        return $like->delete();
    }

    /**
     * Determines if an item is liked or not
     * @return boolean
     */
    public function isLiked()
    {
        $like = Like::model()->findByAttributes(array(
            'user_id' => Yii::app()->user->id,
            'share_id' => $this->id
        ));

        return $like != NULL;
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
    public function search($items = array())
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('text',$this->text,true);
        $criteria->compare('reply_id',$this->reply_id);
        $criteria->compare('created',$this->created);

        if (empty($items))
            $criteria->compare('author_id',$this->author_id);
        else
            $criteria->addInCondition('author_id', $items);

        $criteria->order = 'created DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Share the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
