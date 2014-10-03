<!--nocache-->
<div class="users form">
	<?php echo $this->Form->create("User");?>
	<fieldset>
		<legend><?php echo __('Sign up for Triangles.io');?></legend>
		<?php 
		echo $this->Form->input('username');
		echo $this->Form->input("password");
		echo $this->Form->input("email");
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit'));?>
</div>
<!--/nocache-->