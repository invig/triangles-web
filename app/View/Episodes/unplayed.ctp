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
    <div class="small-12 columns">
            <h1 class="">
                Unplayed Episodes
            </h1>
            <?php if (count($episodes) <= 0) : ?>
                <div class="callout row small-centered columns small-12" style="text-align:center; margin:30px 0;">
                    <p>
                        Podcasts that you have subscribed to that are not finished will be displayed here ordered by date published.
                    </p>
                </div>
            <?php endif; ?>
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
                    <div class="small-3 columns">
                        <img src="<?php echo "/ssl_proxy.php?url=". rawurlencode( $episode['Podcast']['artwork_url'] ); ?>" />
                    </div>
                    <div class="small-6 columns">
                        <h4><?php echo $episode['Episode']['title']; ?></h4>
                        <p><?php
                            $description = $episode['Episode']['description'];
                            $count = strlen($description);
                            if ($count > 200) {
                                $description = substr($description, 0, 197)."...";
                            }
                            echo $description;
                            ?>
                        </p>
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
<?php if(count($episodes) > 0 ) : ?>
<div class="row columns small-2 small-centered">
    <div style="padding: 30px 0">
        <?php echo $this->Paginator->numbers(array('first' => 'First page')); ?>
    </div>
</div>
<div class="row columns small-3 small-centered">
    <div style="padding: 30px; text-align:center;">
        <button data-closable class="button secondary hollow" data-open="mark-all-played-box">Mark all as played</button>
        <div id="mark-all-played-box" class="alert callout reveal"  data-reveal>
            <h5>Mark all as played</h5>
            <p>Are you sure you want to mark <span style="font-weight:bold">all</span> of your unplayed episodes as played?</p>
            <a class="alert button" href="/plays/mark_all_finished">Yes</a>
            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <button data-closable class="button secondary hollow" data-open="mark-all-except-played-box">Mark old as played</button>
        <div id="mark-all-except-played-box" class="alert callout reveal"  data-reveal>
            <h5>Mark old as played</h5>
            <p>Are you sure you want to mark all of your unplayed episodes as played, except the most recent episode from each podcast?</p>
            <a class="alert button" href="/plays/mark_all_except_most_recent_finished">Yes</a>
            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>