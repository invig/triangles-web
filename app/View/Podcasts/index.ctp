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
<div class="row">
	<div class="small-12 columns small-centered th" id="add_podcast" style="padding:20px;">
		<h4 style="padding:0 0 20px;">Add a podcast</h4>
		<?php echo $this->Form->create('Podcast', array('controller'=>'podcast','action'=>'add'));?>
		<?php echo $this->Form->input('url', array('label' => 'Feed URL:')); ?>
		<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Add')); ?>
	</div>
</div>