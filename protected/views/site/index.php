<div class="homepage-container">
	<div class="about-socialii-container">
		<h1>Welcome to Socialii</h1>
		<p>Start a conversation, keep in touch with friends, and explore your interests.</p>
	</div>
	<div class="right-container">
		<!-- Login Form :: Post to site/login -->
		<div class="white-box">
			<h3>Login to Socialii</h3>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'					=>	'login-form',
				'focus'					=> 'input[type="text"]:first',
				'enableAjaxValidation'	=>	true,
				'action'                => $this->createUrl('site/login'),
				'htmlOptions' => array(
					'role' => 'form'
				)
			)); ?>
				<div class="form-group">
					<?php echo $form->textField($loginform,'username', array('class' => 'form-control', 'placeholder' => 'Username')); ?>
				</div>
				<div class="form-group">
					<?php echo $form->passwordField($loginform,'password', array('class' => 'form-control', 'placeholder' => 'Password')); ?>
				</div>
				<p><?php echo CHtml::link('Forgot your password?', $this->createUrl('user/forgot'), array('class' => 'pull-right')); ?></p>
				<?php echo CHtml::tag('button', array('class' => 'btn btn-primary btn-block'), 'Login'); ?>
			<?php $this->endWidget(); ?>
		</div>

		<!-- Registration Form :: Post to user/register -->
		<div class="white-box">
			<h3>Need a Socialii Account?</h3>
			<p>Signup here for a free account.</p>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'					=>	'login-form',
				'enableAjaxValidation'	=>	true,
				'action'                => $this->createUrl('user/register')
			)); ?>
				<div class="form-group">
					<?php echo $form->textField($user,'email', array('class' => 'form-control', 'placeholder' => 'Your Email address')); ?>
				</div>
                <div class="form-group">
					<?php echo $form->textField($user,'username', array('class' => 'form-control', 'placeholder' => 'Username')); ?>
				</div>
				<div class="form-group">
					<?php echo $form->passwordField($user,'password', array('class' => 'form-control', 'placeholder' => 'Password')); ?>
				</div>
				<div class="form-group">
					<?php echo $form->textField($user,'name', array('class' => 'form-control', 'placeholder' => 'Your Full Name')); ?>
				</div>
				<?php echo CHtml::tag('button', array('class' => 'btn btn-primary btn-block'), 'Register'); ?>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
