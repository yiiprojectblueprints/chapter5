<div>
	<h1>Hi <?php echo $user->name; ?></h1><br />
	<p>You were recently @mentioned on <?php echo CHtml::link('Socialii', $this->createAbsoluteUrl('site/index')); ?>.</p>
	<p>
        <?php
            $share->text = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"" . Yii::app()->controller->createAbsoluteUrl('timeline/search') ."?q=$1\">#$1</a>", $share->text);
            $share->text = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"" . Yii::app()->controller->createAbsoluteUrl('timeline/index'). "/$1\">@$1</a>", $share->text);
            echo $share->text;
        ?>
    </p>

    <p>Reply to this share on <?php echo CHtml::link('Socialii', $this->createAbsoluteUrl('share/view', array('id' => $share->id))); ?></p>
</div>
