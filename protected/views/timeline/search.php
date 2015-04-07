<div class="homepage-container">
    <div class="white-box">
        <h2>Find What You're Looking For</h2>
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'					=>	'login-form',
            'enableAjaxValidation'	=>	true,
            'action'                => $this->createUrl('timeline/search'),
            'method'                => 'get',
            'htmlOptions' => array(
                'id' => 'form-group'
            )
        )); ?>
            <input type="text" name="q" placeholder="<?php echo isset($_GET['q']) ? $_GET['q'] : NULL?>" placeholder="Search for something..." class="form-control"/>
            <br />
            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary pull-right')); ?>
        <?php $this->endWidget(); ?>
        <div class="clearfix"></div>
    </div>

    <?php if ($users != NULL): ?>
        <div class="white-box">
            <h3>Users</h3>
            <?php foreach ($users as $user): ?>
                <?php $this->renderPartial('//user/list', array('user' => $user)); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($shares != NULL): ?>
        <div class="white-box">
            <h3>Shares</h3>
            <?php foreach ($shares as $share): ?>
                <?php $this->renderPartial('//share/share', array('data' => $share)); ?>
            <?php endforeach; ?>
            <p class="center">Refine your search to get more results</p>
        </div>
    <?php endif; ?>
</div>
