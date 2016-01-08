<?php if (isset($current_episode)) : ?>
<div class="row">
		<a href="/episodes/play/<?php echo $current_episode['Episode']['id']; ?>" class="small-12 columns th" style="padding:10px 20px 20px; margin:10px;">
			<h4 class="row" style="margin: 0; padding: 0 0 10px 0;">Most recent play:</h4>
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
	<div class="small-4 columns"><h1>Your Podcasts</h1></div>
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
	<div class="small-10 columns small-centered" id="add_podcast">
		<h3>Add a podcast</h3>
		<?php echo $this->Form->create('Podcast', array('controller'=>'podcast','action'=>'add'));?>
		<fieldset>
			<?php echo $this->Form->input('url', array('label' => 'Feed URL:')); ?>
		</fieldset>
		<?php echo $this->Form->end(array('class'=>'radius button right', 'label'=>'Add')); ?>
	</div>
</div>