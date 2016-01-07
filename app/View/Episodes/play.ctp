
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

<script type="text/javascript">
(function() {
    var url = "/plays/update_play_state";
    var episode_id = <?php echo $episode['Episode']['id']; ?>;
    var player = document.getElementById('player');
    var currentTime = <?php
        if (isset($play)) {
            echo $play['Play']['position'];
        } else {
            echo 0;
        }
        ?>;
    var httpRequest;

    player.addEventListener('canplay', function(e) {
        console.log('Current time: ' + currentTime);
        player.fastSeek(currentTime);
    });

    player.addEventListener('timeupdate', function(e) {
        if (player.currentTime > currentTime + 10) {
            setTimeOnEpisode(player.currentTime);
            currentTime = player.currentTime;
        }
    });

    player.addEventListener('seeked', function(e) {
       currentTime = player.currentTime;
    });

    function setTimeOnEpisode(time) {
        makeRequest('episode_id=' + encodeURIComponent(episode_id) + '&position=' + encodeURIComponent(time));
        console.log('Playing episode with id: ' + episode_id + ' at time: ' + time);
    }

    function makeRequest(sendString) {
        httpRequest = new XMLHttpRequest();

        if (!httpRequest) {
            console.log('Failed to save playback position');
            return false;
        }
        httpRequest.onreadystatechange = alertContents;
        httpRequest.open('POST', url);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send(sendString);
    }

    function alertContents() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200) {
                console.log(httpRequest.responseText);
            } else {
                console.log('Failed to save playback position, error:' + httpRequest.status);
            }
        }
    }
})();
</script>