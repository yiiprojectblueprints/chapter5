<?php

class ShareController extends CController
{
	/**
	 * AccessControl filter
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * AccessRules
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('view', 'getshares'),
				'users'=>array('*'),
			),
            array('allow',
                'actions' => array('create', 'reshare', 'like', 'delete', 'hybrid'),
                'users' => array('@')
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionHybrid($id=NULL)
    {
        if (isset($_GET['hauth_start']) || isset($_GET['hauth_done']))
            Hybrid_Endpoint::process();

        try {
            // Load the config
            $config = Yii::app()->params['includes']['hybridauth'];
            $config['baseUrl'] = $config['base_url'] = $this->createAbsoluteUrl('share/hybrid');

            $hybridauth = new Hybrid_Auth($config, array());

            $adapter = $hybridauth->authenticate('Twitter');

            // Load the share
            $share = $this->loadModel($id);
            $response = $adapter->setUserStatus($share->text . ' | #Socialii ' . $this->createAbsoluteUrl('share/view', array('id' => $id)));

            Yii::app()->user->setFlash('success', 'Your status has been shared to Twitter');
            $this->redirect(Yii::app()->user->returnUrl);

        } catch (Exception $e) {
			Yii::app()->user->setFlash('success', 'Your status has been shared to Twitter');
            $this->redirect($this->createUrl('timeline/index'));
        }
    }

    /**
     * Likes a share
     * @param int $id     The share ID
     */
    public function actionLike($id=NULL)
    {
        $share = $this->loadModel($id);
        if ($share->isLiked())
            return $share->unlike();

        return $share->like();
    }

    /**
     * Reshares a post, if it exists
     * @param int $id     The share ID
     */
    public function actionReshare($id=NULL)
    {
        // Load the share model
        $share = $this->loadModel($id);

        // You can't reshare your own stuff
        if ($share->author_id == Yii::app()->user->id)
            return false;

        // You can't reshare stuff you've already re-shared
        $reshare = Share::model()->findByAttributes(array(
            'author_id' => Yii::app()->user->id,
            'reshare_id' => $id
        ));

        if ($reshare !== NULL)
            return false;

        // Create a new Share as a reshare
        $model = new Share;

        // Assign the shared attributes
        $model->attributes = $share->attributes;

        // Set the reshare other to the current user
        $model->author_id = Yii::app()->user->id;

        // Propogate the reshare if this isn't original
        if ($model->reshare_id == 0 || $model->reshare_id == NULL)
            $model->reshare_id = $share->id;

        // Then save the reshare, return the response. Yii will set a 200 or 500 response code automagically if false
        return $model->save();
    }

    /**
     * Deletes a share, if it belongs to the current user
     * @param int $id     The share ID
     */
    public function actionDelete($id=NULL)
    {
        $share = $this->loadModel($id);

        // Delete the share only if it belongs to this user
        if ($share->author_id == Yii::app()->user->id)
            return $share->delete();

        return false;
    }

    /**
     * Allows us to view a particular share, and the associated replies
     * @param int $id     The ID of the share
     */
	public function actionView($id=NULL)
    {
        $share = $this->loadModel($id);

        if ($share == NULL)
            throw new CHttpException(400, 'No share with that ID was found');

        $this->render('view', array(
            'share' => $share,
            'replies' => Share::model()->findAllByAttributes(array('reply_id' => $id), array('order' => 'created DESC')),
            'reply' => new Share
        ));
    }

    /**
     * Retrieves the shares for a given user
     * @param int $id     The ID of the user we want to retrieve the shares for
     */
    public function actionGetShares($id=NULL)
    {
        // Disable the layout rendering
        $this->layout = false;

        if ($id == NULL)
        {
            if (Yii::app()->user->isGuest)
                throw new CHttpException(400, 'Cannot retrieve shares for that user');

            $id = Yii::app()->user->id;
        }

        $myFollowers = array();

        // CListView for showing shares
        $shares = new Share('search');
        $shares->unsetAttributes();

        if(isset($_GET['Share']))
            $shares->attributes=$_GET['Share'];

        // If this is NOT the current user, then only show stuff that belongs to this user
        if ($id != Yii::app()->user->id)
            $shares->author_id = $id;
        else
        {
            // Alter the criteria to do a search of everyone the current user is following
            $myFollowers[] = Yii::app()->user->id;

            $followers = Follower::model()->findAllByAttributes(array('follower_id' => Yii::app()->user->id));
            if ($followers != NULL)
            {
                foreach ($followers as $follower)
                    $myFollowers[] = $follower->followee_id;
            }
        }

        $this->render('getshares', array('shares' => $shares, 'myFollowers' => $myFollowers));
    }

    /**
     * Allows authenticated users to share something
     * @return JSON response
     */
    public function actionCreate()
    {
        $share = new Share;

        if (isset($_POST['Share']))
        {
            $share->attributes = array(
                'text' => $_POST['Share']['text'],
                'reply_id' => isset($_POST['Share']['reply_id']) ? $_POST['Share']['reply_id'] : NULL,
                'author_id' => Yii::app()->user->id
            );

            // Share the content
            if ($share->save())
            {
                $this->renderPartial('share', array('data' => $share));
                Yii::app()->end();
            }
        }

        throw new CHttpException(500, 'There was an error sharing your content');
    }

    /**
     * Returns a share model
     * @param int $id           The Share ID
     * @return Share $model     The Share Model
     */
    private function loadModel($id=NULL)
    {
        if ($id == NULL)
            throw new CHttpException(400, 'Missing Share ID');

        return Share::model()->findByPk($id);
    }
}
