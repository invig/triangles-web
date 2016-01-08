<?php if (isset($current_episode)) : ?>
<div class="row">
		<a href="/episodes/play/<?php echo $current_episode['Episode']['id']; ?>" class="small-12 columns th" style="padding:10px 20px 20px; margin:10px 0;">
			<h4 class="row" style="margin: 0; padding: 0 0 10px 0;">Recently playing:</h4>
			<div class="row">
				<div class="small-2 columns">
					<img src="<?php echo $current_episode['Podcast']['artwork_url']; ?>" />
				</div>
				<div class="small-10 columns">
					<h4><?php echo $current_episode['Episode']['title']; ?></h4>
					<p class="description"><?php echo $current_episode['Episode']['description']; ?></p>
				</div>
			</div>
		</a>
</div>
<?php endif; ?>
<div class="row">
	<div class="small-12 columns"><h1>Podcasts</h1></div>
</div>
<?php if (count($users_podcasts) > 0) : ?>
<div class="row">
	<div class="small-11 columns small-centered" id="podcasts">	
		<ul class="inline-list">
			<?php 
			foreach ($users_podcasts as $users_podcast) {
				$podcast = $users_podcast['Podcast'];
				?>
				<li style="margin:10px">
					<a class="th" href="<?php echo "/episodes/index/". $podcast['id'] ;?>">
						<img src="<?php echo $podcast['artwork_url']; ?> " height="120px" width="120px">
					</a>
				</li>
				<?php				
			}
			?>
		</ul>
	</div>
</div>
<?php else: ?>
<div class="row">
	<div class="small-12 columns centered">
		<p>Podcasts added to Triangles will appear here.</p>
		<?php // TODO: Add a better default state. ?>
	</div>
</div>
<?php endif; ?>

<ul class="tabs" data-tab>
	<li class="tab-title active"><a href="#panel1">Tab 1</a></li>
	<li class="tab-title"><a href="#panel2">Tab 2</a></li>
	<li class="tab-title"><a href="#panel3">Tab 3</a></li>
	<li class="tab-title"><a href="#panel4">Tab 4</a></li>
</ul>
<div class="tabs-content">
	<div class="content active" id="panel1">
		<p>This is the first panel of the basic tab example. You can place all sorts of content here including a grid.</p>
	</div>
	<div class="content" id="panel2">
		<p>This is the second panel of the basic tab example. This is the second panel of the basic tab example.</p>
	</div>
	<div class="content" id="panel3">
		<p>This is the third panel of the basic tab example. This is the third panel of the basic tab example.</p>
	</div>
	<div class="content" id="panel4">
		<p>This is the fourth panel of the basic tab example. This is the fourth panel of the basic tab example.</p>
	</div>
</div>


<div class="row">
	<div class="small-12 columns small-centered th" id="add_podcast" style="padding:20px;">
		<h4 style="padding:0 0 20px;">Add a podcast</h4>
		<?php echo $this->Form->create('Podcast', array('controller'=>'podcast','action'=>'add'));?>
		<?php echo $this->Form->input('url', array('label' => 'Feed URL:')); ?>
		<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Add')); ?>
	</div>
</div>
<div class="row">
	<div class="small-12 columns small-centered th" id="add_podcast" style="padding:20px;">
		<h4 style="padding:0 0 20px;">Import podcast list from OPML</h4>
		<?php echo $this->Form->create('Podcast', array('controller'=>'podcast','action'=>'add'));?>
		<?php echo $this->Form->input('url', array('label' => 'Feed URL:')); ?>
		<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Add')); ?>
	</div>
</div>