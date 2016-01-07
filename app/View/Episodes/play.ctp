
<div class="row">
    <div class="small-8 columns small-centered" id="podcasts">
        <div class="row">
            <div class="small-12 columns artwork">
                <img src="<?php echo $episode['Podcast']['artwork_url']; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns episode-description">
                <h4><?php echo $episode['Episode']['title']; ?></h4>
                <p class="description"><?php echo $episode['Episode']['description']; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns podcast-player-wrapper">
                <audio id="player" class="podcast-player" src="<?php echo $episode['Episode']['url']?>" controls="controls"></audio>
            </div>
        </div>
    </div>
</div>

<script>
    var episode_id = <?php echo $episode['Episode']['id']; ?>;
    var player = document.getElementById('player');
    var currentTime = 0;

    player.addEventListener('timeupdate', function(e) {
        if (player.currentTime > currentTime + 10) {
            setTimeOnEpisode(player.currentTime);
            currentTime = player.currentTime;
        }
    });

    function setTimeOnEpisode(time) {
        // TODO: Fire an ajax update.
        console.log('Playing episode with id: ' + episode_id + ' at time: ' + time);
    }
</script>