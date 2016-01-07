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
<div class="row">
    <div class="small-6 columns small-centered" id="podcasts">
        <ul class="list">
            <?php
            foreach ($episodes as $episode) {
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