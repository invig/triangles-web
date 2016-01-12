<?php if (isset($current_episode)) : ?>
    <div class="row expanded" data-closable>
        <div class="callout" style="text-align: center">
            Recently playing: <a href="/episodes/play/<?php echo $current_episode['Episode']['id']; ?>" class="">
                <?php echo $current_episode['Episode']['title']; ?>
            </a>
            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php endif; ?>
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
    <div class="small-9 columns small-centered" id="podcasts">
        <h3 class="row played-status-title">Unplayed Episodes</h3>
        <ul class="list">
            <?php
            foreach ($unplayed_episodes as $episode) {
            ?>
                <li class="row">
                    <div class="small-6 columns">
                        <h4><?php echo $episode['Episode']['title']; ?></h4>
                        <p class="description"><?php echo $episode['Episode']['description']; ?></p>
                    </div>
                    <div class="small-3 columns">
                        <div class="button-group">
                            <a class="tiny button success" href="/episodes/play/<?php echo $episode['Episode']['id']; ?>">Play</a>
                            <a class="dropdown button arrow-only success" data-toggle="episode-dropdown-<?php echo $episode['Episode']['id']; ?>">
                                <span class="show-for-sr">Show menu</span>
                            </a>
                            <div class="dropdown-pane" id="episode-dropdown-<?php echo $episode['Episode']['id']; ?>" data-dropdown data-auto-focus="true">
                                <a class="success" href="/plays/mark_finished/<?php echo $episode['Episode']['id']; ?>">Mark finished</a>
                            </div>
                        </div>
                    </div>
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
    <div class="small-9 columns small-centered" id="podcasts">
        <h3 class="row played-status-title">Played Episodes</h3>
        <ul class="list">
            <?php
            foreach ($played_episodes as $episode) {
                ?>
                <li class="row">
                    <div class="small-6 columns">
                         <h4><?php echo $episode['Episode']['title']; ?></h4>
                        <p class="description"><?php echo $episode['Episode']['description']; ?></p>
                    </div>
                    <div class="small-3 columns">
                        <div class="button-group">
                            <a class="tiny button success" href="/episodes/play/<?php echo $episode['Episode']['id']; ?>">Play</a>
                            <a class="dropdown button arrow-only success" data-toggle="episode-dropdown-<?php echo $episode['Episode']['id']; ?>">
                                <span class="show-for-sr">Show menu</span>
                            </a>
                            <div class="dropdown-pane" id="episode-dropdown-<?php echo $episode['Episode']['id']; ?>" data-dropdown data-auto-focus="true">
                                <a class="success" href="/plays/mark_unfinished/<?php echo $episode['Episode']['id']; ?>">Mark unfinished</a>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<?php endif; ?>
<div class="row columns small-2 small-centered">
    <?php echo $this->Paginator->numbers(array('first' => 'First page')); ?>
</div>
<div style="padding-top:30px;"></div>
