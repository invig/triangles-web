<?php

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<link rel="apple-touch-icon" href="triangle-icon-64.png">
	<link rel="apple-touch-icon" sizes="20x20" href="/img/triangle-icon-20.png">
	<link rel="apple-touch-icon" sizes="24x24" href="/img/triangle-icon-24.png">
	<link rel="apple-touch-icon" sizes="32x32" href="/img/triangle-icon-32.png">
	<link rel="apple-touch-icon" sizes="48x48" href="/img/triangle-icon-48.png">
	<link rel="apple-touch-icon" sizes="64x64" href="/img/triangle-icon-64.png">
	<link rel="apple-touch-icon" sizes="128x128" href="/img/triangle-icon-128.png">
	<link rel="apple-touch-icon" sizes="256x256" href="/img/triangle-icon-256.png">
	<link rel="apple-touch-icon" sizes="512x512" href="/img/triangle-icon-512.png">
	<link rel="icon"
		  type="image/png"
		  href="/img/triangle-icon-32.png">
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
			<div class="top-bar" id="nav" data-dropdown-menu >
				<ul class="menu vertical medium-horizontal" 	data-responsive-menu="drilldown medium-dropdown">
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
		</div>
		<div id="content">
			<?php
			$flash = $this->Session->flash();
			if (! empty($flash)) : ?>
			<div class="row columns small-12">
				<div class="alert callout" data-closable>
					<h5>Alert</h5>
					<p><?php echo $flash; ?></p>
					<button class="close-button" aria-label="Dismiss alert" type="button" data-close>
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			<?php endif; ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" style="text-align:center; font-size:0.6em; padding:30px; color: #bbb">
			<p>Created by Matt Salmon - <a href="http://lab82.com">Lab82</a><br />Source available on <a href="https://github.com/invig/triangles-web">Github</a></p>
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