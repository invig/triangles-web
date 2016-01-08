<div class="row">
	<div class="small-12 columns"><h1>Podcasts</h1></div>
</div>
<?php if (isset($current_episode)) : ?>
	<div class="row">
		<a href="/episodes/play/<?php echo $current_episode['Episode']['id']; ?>" class="small-12 medium-8 medium-centered columns" style="display:block; border:1px solid; padding:10px;">
			<h4>Recently playing:</h4>
			<div class="row">
				<div class="small-2 medium-3 large-2 columns">
					<img src="<?php echo $current_episode['Podcast']['artwork_url']; ?>" />
				</div>
				<div class="small-10 medium-9 large-10 columns">
					<h4><?php echo $current_episode['Episode']['title']; ?></h4>
					<p class="description"><?php echo $current_episode['Episode']['description']; ?></p>
				</div>
			</div>
		</a>
	</div>
<?php endif; ?>
<?php if (count($users_podcasts) > 0) : ?>
<div class="row">
	<div class="small-11 columns small-centered" id="podcasts">	
		<ul class="grid">
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

<div class="row">
	<ul class="tabs" data-tabs id="example-tabs">
		<li class="tabs-title is-active"><a href="#add-podcast" aria-selected="true">Add a podcast</a></li>
		<li class="tabs-title"><a href="#import-opml">Import from OPML</a></li>
	</ul>

	<div class="tabs-content" data-tabs-content="add-tabs">
		<div class="tabs-panel is-active" id="add-podcast">
			<div class="small-12 columns small-centered th" id="add_podcast" style="padding:20px;">
				<h4 style="padding:0 0 20px;">Add a podcast</h4>
				<?php echo $this->Form->create('Podcast', array('controller'=>'podcast','action'=>'add'));?>
				<?php echo $this->Form->input('url', array('label' => 'Feed URL:')); ?>
				<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Add')); ?>
			</div>
		</div>
		<div class="tabs-panel" id="import-opml">
			<div class="small-12 columns small-centered th" id="add_podcast" style="padding:20px;">
				<h4 style="padding:0 0 20px;">Import podcast list from OPML</h4>
				<?php echo $this->Form->create('Podcast', array('controller'=>'podcast','action'=>'import'));?>
				<?php echo $this->Form->input('opml_file', array('label' => 'OPML File:', 'type' => 'file')); ?>
				<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Import')); ?>
			</div>
		</div>
	</div>
</div>
