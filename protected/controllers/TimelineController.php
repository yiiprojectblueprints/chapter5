<?php

class TimelineController extends CController
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
				'actions' => array('index', 'search'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Main timeline action
	 * Allows the user to see a timeline of a particular user
	 */
	public function actionIndex($id = NULL)
	{
        // If the ID is not set, set this to the currently logged in user.
		if ($id == NULL)
		{
			if (Yii::app()->user->isGuest)
				$this->redirect($this->createUrl('site/login'));

			$id = Yii::app()->user->username;
		}

        // Get the user's information
		$user = User::model()->findByAttributes(array('username' => $id));
        if ($user == NULL)
            throw new CHttpException(400, 'Unable to find a user with that ID');

		$this->render('index', array(
            'user' => $user,
            'share' => new Share,
            'id' => $user->id
        ));
	}

    /**
     * Searches for stuff
     */
    public function actionSearch()
    {
        $query = isset($_GET['q']) ? $_GET['q'] : NULL;

        // Scope
        $users = $shares = NULL;

        if ($query != NULL)
        {
            // Make the criteria object
            $userCriteria = new CDbCriteria;
            $searchCriteria = new CDbCriteria;

            // If there is a @ symbol, do a user search
            preg_match_all('/@([A-Za-z0-9\/\.]*)/', $query, $matches);
            $mentions = implode(',', $matches[1]);
            if (!empty($matches[1]))
            {
                $userCriteria->addInCondition('username', $matches[1]);
                $users = User::model()->findAll($userCriteria);

                // Remove the @users from the remaining query
                foreach ($matches[1] as $u)
                    $query = str_replace('@'.$u,'',$query);
            }

            // Do a like Query
            $searchCriteria->addSearchCondition('text', $query);
            $searchCriteria->limit = 30;

            $shares = Share::model()->findAll($searchCriteria);
        }

        // Render the search
        $this->render('search', array(
            'users' => $users,
            'shares' => $shares
        ));
    }
}
