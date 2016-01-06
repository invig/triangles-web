<div class="row">
	<div class="small-4 columns"><h1>Your Podcasts</h1></div>
</div>
<div class="row">
	<div class="small-11 columns small-centered" id="podcasts">	
		<ul class="inline-list">
			<?php 
			foreach ($users_podcasts as $users_podcast) {
				$podcast = $users_podcast['Podcast'];
				//TODO: Add link to podcast episode list
				?>
				<li style="margin:10px">
					<a class="th" href="">
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