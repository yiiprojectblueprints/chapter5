<div class="homepage-container">
    <div class="left-sidebar pull-left">
        <div class="share-box white-box">
            <h4>Share Something</h4>
            <div class="share-container">
                <div class="pull-left profile-image">
                    <?php echo CHtml::image("https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?&s=50&d=mm"); ?>
                </div>
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'					=>	'login-form',
                    'enableAjaxValidation'	=>	true,
                    'action'                => $this->createUrl('share/create'),
                    'htmlOptions' => array(
                        'id' => 'form-group'
                    )
                )); ?>

                <div class="pull-left">
                    <?php echo $form->textArea($share, 'text', array('placeholder' => 'Share your thoughts here', 'class' => 'form-control')); ?>
                </div>
                <div class="clearfix"></div>
                <br />
                <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary pull-right')); ?>
                <div class="clearfix"></div>
                <?php $this->endWidget(); ?>
            </div>
        </div>

        <?php if (Yii::app()->user->id != $id): ?>
            <!-- Show actionables to logged in users who are not the current user -->
            <div class="actionables">
                <?php if (Yii::app()->user->isGuest): ?>
                    <?php echo CHtml::link('Login to follow ' . $user->name, $this->createUrl('site/login'), array('class' => 'btn btn-primary')); ?>
                        <br /><br />
                <?php else: ?>
                    <?php if (!User::isFollowing($id)): ?>
                        <?php echo CHtml::link('Follow This User', $this->createUrl('user/follow/', array('id' => $id)), array('class' => 'btn btn-success')); ?>
                    <?php else: ?>
                        <?php echo CHtml::link('Stop Following This User', $this->createUrl('user/unfollow/', array('id' => $id)), array('class' => 'btn btn-danger')); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="information">
            <div class="btn-group btn-group-justified">
                <a type="button" class="btn btn-primary" disabled>Followers: <?php echo $user->followeesCount; ?></a>
                <a type="button" class="btn btn-primary" disabled>Following: <?php echo $user->followersCount; ?></a>
                <a type="button" class="btn btn-primary" disabled>Shares: <span class="share-count"><?php echo $user->sharesCount; ?></span></a>
            </div>
        </div>
    </div>
    <div class="shares white-box pull-left">
       <?php Yii::app()->clientScript->registerScript('loadshares', '$.get("' . $this->createUrl('share/getshares', array('id' => $id)) . '", function(data) { $(".shares").html(data); }); '); ?>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('share', '
    $("form").submit(function(e) {
        e.preventDefault();

        if ($("#Share_text").val() == "")
        {
            $("form").addClass("has-error");

            setTimeout(function() { $("form").removeClass("has-error"); }, 2000);
            return false;
        }

        $.post($("form").attr("action"), $("form").serialize(), function(data) {
            $(".items").prepend(data);
            $("#Share_text").val("");
            $(".share-count").text(parseInt($(".share-count").text()) + 1);
        });

        return false;
    });
'); ?>
