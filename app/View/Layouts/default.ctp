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
		// echo $this->Html->css('cake.generic');
		echo $this->Html->css('foundation.css');
		echo $this->Html->script('vendor/modernizr.js');
		echo $this->Html->css('custom.css');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<nav class="top-bar" data-topbar>
				<ul class="title-area">
					<li class="name">
						<h1><a href="#">Triangles</a></h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
				</ul>
				<section class="top-bar-section">
					<!--nocache-->
					<?php if ($logged_in) :?>				
						<ul class="left">
							<li><?php echo $this->Html->link('Podcasts', array('controller'=>'podcasts', 'action'=>'index')); ?></li>
							<li><?php echo $this->Html->link('Unplayed', array('controller'=>'episodes', 'action'=>'unplayed')); ?></li>
						</ul>
					<?php endif;?>					
				   	<ul class="right">
						<?php if ($logged_in) :?>
							<li><?php echo $this->Html->link('Log out', array('controller'=>'users', 'action'=>'logout')); ?></li>
							<li class="has-dropdown">
								<a href="#">Account</a>
								<ul class="dropdown">
	  								<li><?php echo $this->Html->link('Edit profile', array('controller'=>'users', 'action'=>'edit')); ?></li>
								</ul>
							</li>
						<?php else : ?>
							<li><?php echo $this->Html->link('Log in', array('controller'=>'users', 'action'=>'login')); ?></li>
							<li><?php echo $this->Html->link('Sign up', array('controller'=>'users', 'action'=>'signup')); ?></li>
						<?php endif;?>
					</ul>
					<!--/nocache-->					
				</section>
			</nav>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">

		</div>
	</div>
	<?php
		echo $this->Html->script('vendor/jquery.js');
		echo $this->Html->script('foundation.min.js');
	?>
    <script>
      $(document).foundation();
    </script>
</body>
</html>