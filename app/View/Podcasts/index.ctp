<?php if (isset($current_episode)) : ?>
	<div class="row expanded" data-closable>
		<div class="callout" style="text-align: center">
			Recently playing: <a href="/episodes/play/<?php echo $current_episode['Episode']['id']; ?>" class="">
				<?php echo $current_episode['Episode']['title']; ?>
			</a>
			<button class="close-button show-for-medium" aria-label="Dismiss alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
<?php endif; ?>
<div class="row columns small-12">
	<h1 style="margin: 30px 0 0">Podcasts</h1>
</div>
<?php if (count($users_podcasts) > 0) : ?>
<div class="row">
	<div class="small-12 columns small-centered" id="podcasts">
		<ul class="grid">
			<?php 
			foreach ($users_podcasts as $users_podcast) {
				$podcast = $users_podcast['Podcast'];
				?>
				<li>
					<a class="th" href="<?php echo "/episodes/index/". $podcast['id'] ;?>">
						<img src="<?php echo "/ssl_proxy.php?url=". rawurlencode( $podcast['artwork_url'] ); ?> " height="160px" width="160px">
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
		<div class="grid">
			<p>Podcasts added to Triangles will appear here.</p>
		</div>
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
				<?php echo $this->Form->create(null, array('controller'=>'podcast','action'=>'import', 'type' => 'file'));?>
				<?php echo $this->Form->input('opml_file', array('label' => 'OPML File:', 'type' => 'file')); ?>
				<p>Warning: it may take some time to import your podcasts.</p>
				<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Import')); ?>
			</div>
		</div>
	</div>
</div>
