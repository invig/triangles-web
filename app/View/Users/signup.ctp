<!--nocache-->
<div class="users form row">
	<div class="columns small-12 th" style="margin-top:10px; padding:20px;">
		<h1 style="margin-bottom:20px;">Sign up</h1>
		<?php
		echo $this->Form->create("User");
		echo $this->Form->input('username');
		echo $this->Form->input("password");
		echo $this->Form->input("email");
		echo $this->Form->end(__('Submit'));
		?>
	</div>
</div>
<!--/nocache-->
