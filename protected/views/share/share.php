<div class="share" data-attr-id="<?php echo $data->id; ?>">
    <div class="profile-photo pull-left">
       <?php echo CHtml::image("https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $data->author->email ) ) ) . "?&s=50&d=mm"); ?>
    </div>
    <div class="pull-left">
           <span data-livestamp="<?php echo $data->created; ?>"></span>
           <div class="share-body">
               <?php
                    $data->text = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"" . Yii::app()->controller->createAbsoluteUrl('timeline/search') ."?q=$1\">#$1</a>", $data->text);
                    $data->text = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"" . Yii::app()->controller->createAbsoluteUrl('timeline/index'). "/$1\">@$1</a>", $data->text);
					$md = new CMarkdownParser;
                    echo $md->safeTransform($data->text);
               ?>
           </div>
           <div class="actions">
               <span class="fa fa-heart <?php echo $data->isLiked() ? 'liked' : NULL; ?>"></span>
               <?php if ($data->author_id != Yii::app()->user->id): ?>
                   <span class="fa fa-mail-forward"></span>
               <?php endif; ?>
               <?php if ($data->reshare_id != NULL): ?>
                   <span class="fa fa-share liked"></span> by <?php echo CHtml::link(Share::model()->findByPk($data->reshare_id)->author->username, $this->createUrl('share/view', array('id' => $data->reshare_id))); ?>
               <?php endif; ?>
               <?php if (!Yii::app()->user->isGuest): ?>
                   <?php echo CHtml::link(NULL, $this->createUrl('share/hybrid', array('id' => $data->id)), array('class' => 'fa fa-twitter')); ?>
               <?php endif; ?>
			   <?php echo CHtml::link(NULL,$this->createUrl('share/view', array('id' => $data->id)), array('class' => 'fa fa-eye')); ?>
           </div>
    </div>
    <div class="clearfix"></div>
</div>
