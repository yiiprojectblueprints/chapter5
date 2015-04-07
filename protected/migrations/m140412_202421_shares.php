<?php

class m140412_202421_shares extends CDbMigration
{
	// Abstract Column types
	// http://www.yiiframework.com/doc/api/1.1/CDbSchema#getColumnType-detail
	public function safeUp()
	{
		$this->createTable('shares', array(
			'id' 		=> 'pk',
			'text' 		=> 'text not null',
			'author_id' => 'integer',
			'reply_id' 	=> 'integer',
            'reshare_id' => 'integer',
			'created' 	=> 'integer'
		));

		$this->addForeignKey('share_users', 'shares', 'author_id', 'users', 'id', NULL, 'CASCADE', 'CASCADE');

        // Likes
        $this->createTable('likes', array(
            'id' => 'pk',
            'user_id' => 'integer',
            'share_id' => 'integer',
            'created' => 'integer',
            'updated' => 'integer'
        ));

        $this->addForeignKey('user_likes_users', 'likes', 'user_id', 'users', 'id', NULL, 'CASCADE', 'CASCADE');
        $this->addForeignKey('user_likes_shares', 'likes', 'share_id', 'shares', 'id', NULL, 'CASCADE', 'CASCADE');

	}

	public function safeDown()
	{
		return false;
	}
}
