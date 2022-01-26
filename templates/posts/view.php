<?php

namespace CMS;

?>

<main id="posts-view" class="main wrapper wrapper--inner">

    <section id="post" class="section">
        <article class="article post">
            <div class="article__body post__body">
                <h1 class="article__heading post__heading"><?= $this->Data->post[ 'post_title' ] ?></h1>
                <div>
                    <?= _( 'Author:' ) ?> <a href="/user/profile/<?= $this->Data->post[ 'user_username' ] ?>"><?= $this->Data->post[ 'user_username' ] ?></a>
                    <?= _( 'Created:' ) ?> <span><?= $this->Data->formatDateTime( $this->Data->post[ 'post_created' ] ) ?></span>
                </div>
                <p><?= $this->Data->post[ 'post_message' ] ?></p>
            </div>
        </article>
    </section>

</main>
