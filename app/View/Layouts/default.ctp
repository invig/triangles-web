<?php

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('foundation.min.css');
		echo $this->Html->css('app.css');
		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="title-bar" data-responsive-toggle="nav" data-hide-for="medium">
				<button class="menu-icon" type="button" data-toggle></button>
				<div class="title-bar-title">Menu</div>
			</div>
			<div class="top-bar" id="nav">
				<div class="top-bar-left">
					<ul class="dropdown menu" data-dropdown-menu>
						<li class="menu-text">Triangles</li>
						<li><?php echo $this->Html->link('Podcasts', array('controller'=>'podcasts', 'action'=>'index')); ?></li>
						<li><?php echo $this->Html->link('Unplayed', array('controller'=>'episodes', 'action'=>'unplayed')); ?></li>
						<li class="has-submenu">
							<a href="#">User</a>
							<ul class="submenu menu vertical" data-submenu>
								<!--nocache-->
								<?php if ($logged_in) :?>
									<li><?php echo $this->Html->link('Log out', array('controller'=>'users', 'action'=>'logout')); ?></li>
									<li><?php echo $this->Html->link('Edit profile', array('controller'=>'users', 'action'=>'edit')); ?></li>
								<?php else : ?>
									<li><?php echo $this->Html->link('Log in', array('controller'=>'users', 'action'=>'login')); ?></li>
									<li><?php echo $this->Html->link('Sign up', array('controller'=>'users', 'action'=>'signup')); ?></li>
								<?php endif;?>
								<!--/nocache-->
							</ul>
						</li>
					</ul>
				</div>
<!--				<div class="top-bar-right">-->
<!--					<ul class="dropdown menu" data-dropdown-menu>-->
<!--					</ul>-->
<!--				</div>-->
			</div>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">

		</div>
	</div>
	<?php
		echo $this->Html->script('vendor/jquery.min.js');
		echo $this->Html->script('vendor/what-input.min.js');
		echo $this->Html->script('foundation.min.js');
		echo $this->Html->script('app.js');
		echo $this->fetch('script');
	?>
    <script>
      $(document).foundation();
    </script>
</body>
</html>