
<div class="row">
    <div class="small-8 columns small-centered" id="podcasts">
        <div class="row">
            <div class="small-12 columns artwork">
                <img src="<?php echo "/ssl_proxy.php?url=". rawurlencode( $episode['Podcast']['artwork_url'] ); ?>" />
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
    var time_url = "/plays/update_play_state";
    var playing_url = "/plays/set_currently_playing";
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
    var finished = 0;

    function checkFinished() {
        if (player.currentTime + 30 >= player.duration && finished == 0) {
            finished = 1;
            setTimeOnEpisode(player.currentTime);
        }
    }

    player.addEventListener('canplay', function(e) {
        console.log('Current time: ' + currentTime);
        player.fastSeek(currentTime);
        checkFinished();
    });

    player.addEventListener('timeupdate', function(e) {
        if (player.currentTime > currentTime + 10) {
            setTimeOnEpisode(player.currentTime);
            currentTime = player.currentTime;
        }

        checkFinished();
    });

    player.addEventListener('seeked', function(e) {
       currentTime = player.currentTime;
        setTimeOnEpisode(currentTime);
    });

    player.addEventListener('play', function(e) {
       setCurrentlyPlaying(episode_id);
    });

    function setTimeOnEpisode(time) {
        makeRequest('episode_id=' + encodeURIComponent(episode_id) +
                    '&position=' + encodeURIComponent(time) +
                    '&finished=' + encodeURIComponent(finished),
                    time_url
        );
    }

    function setCurrentlyPlaying(episodeId) {
        makeRequest('episode_id=' + encodeURIComponent(episodeId), playing_url);
    }

    function makeRequest(sendString, endpoint_url) {
        httpRequest = new XMLHttpRequest();

        if (!httpRequest) {
            console.log('Failed to save playback position');
            return false;
        }
        httpRequest.onreadystatechange = alertContents;
        httpRequest.open('POST', endpoint_url);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send(sendString);
    }

    function alertContents() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200) {
                console.log('Response: ' + httpRequest.responseText);
            } else {
                console.log('Failed to save playback position, error:' + httpRequest.status);
            }
        }
    }
})();
</script>