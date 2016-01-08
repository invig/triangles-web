<div class="row podcast-preview">
    <div class="small-11 columns">
        <img class="small-2 columns" src="<?php echo $podcast['Podcast']['artwork_url']; ?>" />
        <div class="small-10 columns">
            <h1 class="">
                <?php echo $podcast['Podcast']['title'] ?>
            </h1>
            <p>
                <?php echo $podcast['Podcast']['description'] ?>
            </p>
        </div>
    </div>
</div>
<?php if (count($unplayed_episodes) > 0) : ?>
<div class="row">
    <div class="small-6 columns small-centered" id="podcasts">
        <h3 class="row played-status-title">Unplayed Episodes</h3>
        <ul class="list">
            <?php
            foreach ($unplayed_episodes as $episode) {
            ?>
                <li class="row">
                    <a class="" href="/episodes/play/<?php echo $episode['Episode']['id']; ?>">
                        <h4><?php echo $episode['Episode']['title']; ?></h4>
                        <p class="description"><?php echo $episode['Episode']['description']; ?></p>
                    </a>
                    <a class=""
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>
<?php endif; ?>
<?php if (count($played_episodes) > 0) : ?>
<div class="row">
    <div class="small-6 columns small-centered" id="podcasts">
        <h3 class="row played-status-title">Played Episodes</h3>
        <ul class="list">
            <?php
            foreach ($played_episodes as $episode) {
                ?>
                <li class="row">
                    <a class="" href="/episodes/play/<?php echo $episode['Episode']['id']; ?>">
                        <h4><?php echo $episode['Episode']['title']; ?></h4>
                        <p class="description"><?php echo $episode['Episode']['description']; ?></p>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<?php endif; ?>