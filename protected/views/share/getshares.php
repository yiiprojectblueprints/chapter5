<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$shares->search($myFollowers),
    'itemView'=>'share',
    'emptyText' => '<div class="center">This user hasn\'t shared anything yet!</div>',
    'template' => '{items}{pager}',
    'afterAjaxUpdate' => 'js:function() { init(); }
    ',
    'pager' => array(
        'header' => ' ',
        'selectedPageCssClass' => 'active',
        'htmlOptions' => array('class' => 'pagination')
    )
));

Yii::app()->clientScript->registerScript('init', '
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
