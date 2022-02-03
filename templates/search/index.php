<?php

namespace CMS;

?>
<main>
    <section>
        <h1><?= sprintf( _( 'Search for \'%s\'' ), $_GET[ 'keyword' ] ) ?></h1>
        <ul>

            <?php foreach( $this->Data->entries as $entry ): ?>

                <li>
                    <a href="/user/profile/<?= $entry[ 'username' ] ?>">
                        <span><?= $entry[ 'username' ] ?></span>
                        <span><?= sprintf( _( 'Registered since %s' ), $this->Data->formatDate( $entry[ 'registered' ] ) ) ?></span>
                        <span><?= sprintf( _( '%s Posts' ), $entry[ 'posts' ] ) ?></span>
                        <span><?= sprintf( _( '%s Likes' ), $entry[ 'likes' ] ) ?></span>
                        <span><?= sprintf( _( '%s Comments' ), $entry[ 'comments' ] ) ?></span>
                    </a>
                </li>

            <?php endforeach; ?>

        </ul>
    </section>
</main>
