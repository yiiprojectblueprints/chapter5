<div class="homepage-container">
    <div class="shares shares-outer white-box">
        <?php echo $this->renderPartial('share', array('data' => $share)); ?>

        <h4>Reply To This Share</h4>

        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'					=>	'login-form',
            'enableAjaxValidation'	=>	true,
            'action'                => $this->createUrl('share/create'),
            'htmlOptions' => array(
                'id' => 'form-group'
            )
        )); ?>

            <div class="pull-left" style="width: 100%">
                <?php echo $form->textArea($reply, 'text', array('placeholder' => '@'.$share->author->username.'...', 'class' => 'form-control', 'style'=>'width:100%')); ?>
                <?php echo $form->hiddenField($reply, 'reply_id', array('value' => $share->id)); ?>
            </div>
            <div class="clearfix"></div>
            <br />
            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary pull-right')); ?>
            <div class="clearfix"></div>
        <?php $this->endWidget(); ?>

        <h4>Replies</h4>
        <div id="shares">
            <?php if ($replies != NULL): ?>
                <?php foreach ($replies as $reply): ?>
                    <?php echo $this->renderPartial('share', array('data' => $reply)); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
            $("#shares").prepend(data);
            $("#Share_text").val("");

            // Rebind the click behaviors
            $(".fa-heart").unbind("click");
            $(".fa-mail-forward").unbind("click");
            init();
        });

        return false;
    });
'); ?>
<?php Yii::app()->clientScript->registerScript('init', '
function init() {
    $(".fa-heart").click(function() {
        var id = $(this).parent().parent().parent().attr("data-attr-id");
        var self = this;
        $.post("' . $this->createUrl('share/like') . '/" + id, function(data) {
            $(self).toggleClass("liked");
        });
    });

    $(".fa-mail-forward").click(function() {
        var id = $(this).parent().parent().parent().attr("data-attr-id");
        var self = this;
        $.post("' . $this->createUrl('share/reshare') . '/" + id, function(data) {
            $(self).toggleClass("liked");
        });
    });
}

init();
');
