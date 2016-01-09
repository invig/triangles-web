
<div class="row podcast-preview">
    <div class="small-11 columns">
            <h1 class="">
                Unplayed Episodes
            </h1>
            <p>
                Podcasts your subscribed to that are not finished, ordered by date published.
            </p>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-8 columns small-centered" id="podcasts">
        <ul class="list unplayed-podcast-list">
            <?php
            foreach ($episodes as $episode) {
                ?>
                <li class="row">
                    <a class="" href="/episodes/play/<?php echo $episode['Episode']['id']; ?>">
                        <div class="small-3 columns">
                            <img src="<?php echo "/ssl_proxy.php?url=". rawurlencode( $episode['Podcast']['artwork_url'] ); ?>" />
                        </div>
                        <div class="small-9 columns">
                            <h4><?php echo $episode['Episode']['title']; ?></h4>
                            <p class="description"><?php echo $episode['Episode']['description']; ?></p>
                        </div>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>