
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
                                <p><?php
                                    $description = $episode['Episode']['description'];
                                    $count = strlen($description);
                                    if ($count > 200) {
                                        $description = substr($description, 0, 197)."...";
                                    }
                                    echo $description;
                                    ?></p>
                        </div>
                    </a>
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

<div class="row columns small-8 small-centered">
    <div style="padding: 30px 0">
        <button data-closable class="alert button" data-open="mark-all-played-box">Mark all as played</button>
        <div id="mark-all-played-box" class="alert callout reveal"  data-reveal>
            <h5>This is Important!</h5>
            <p>This will mark all of your unplayed episodes as played.</p>
            <a class="alert button" href="/plays/mark_all_finished">I'm sure</a>
            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<?php endif; ?>