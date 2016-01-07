<!--nocache-->
<div class="users form one columns centered">
	<?php echo $this->Form->create("User");?>
	<fieldset>
		<legend><?php echo __('Edit your profile: ' . $user->data['User']['username']);?></legend>
		<?php 
		echo $this->Form->input('username', array('label' => 'Change your username:'));
		echo $this->Form->input("email", array('label' => 'Change your email address:'));
		echo ($this->Html->link("Reset password", array('action' => 'reset_password')));
	?>
	</fieldset>
	<?php 
	echo $this->Form->end(array('class'=>'radius button', 'label'=>'Save'));?>
</div>
<!--/nocache-->