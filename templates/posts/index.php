<?php

namespace CMS;

?>

<main id="posts" class="main wrapper wrapper--inner">

    <form id="posts-form" class="form box" method="post">
        <div class="form__header">
            <h2><?= _( 'Create a post' ) ?></h2>
            <?php Messages::printFormSuccess( 'create_post' ) ?>
        </div>
        <div class="form__row">
            <label class="form__label" for="title"><?= _( 'Title' ) ?></label>
            <input class="form__input form__input--text" id="title" name="title" type="text">
            <?php Messages::printInputErrors( 'title' ); ?>
        </div>
        <div class="form__row">
            <label class="form__label" for="message"><?= _( 'Message' ) ?></label>
            <textarea class="form__input form__input--textarea" id="message" name="message"></textarea>
            <?php Messages::printInputErrors( 'message' ); ?>
        </div>
        <div class="form__row">
            <input class="form__input form__input--submit" id="submit" name="create_post" type="submit" value="<?= _( 'Post!' ) ?>">
        </div>

    </form>

    <section id="posts-timeline" class="section">
        <?php
            Messages::printFormSuccess( 'delete_post' );
            Messages::printInputErrors( 'delete_post' );
        ?>
        <?php foreach ( $this->Data->posts as $post ): ?>
            <article class="article post">
                <div class="article__body post__body">
                    <h1 class="article__heading post__heading"><a href="/posts/view/<?= $post[ 'post_id' ] ?>"><?= $post[ 'post_title' ] ?></a></h1>
                    <div>
                        <?= _( 'Author:' ) ?> <a href="/user/profile/<?= $post[ 'user_username' ] ?>"><?= $post[ 'user_username' ] ?></a>
                        <?= _( 'Created:' ) ?> <span><?= $this->Data->formatDateTime( $post[ 'post_created' ] ) ?></span>
                    </div>
                    <p><?= $post[ 'post_message' ] ?></p>
                </div>
                <footer class="article__footer post__footer">
                    <?php if ( (int) $post[ 'user_id' ] === Session::getValue( 'login' )[ 'id' ] ): ?>
                        <form class="form form--inline" method="post">
                            <input class="form__input form__input--hidden" type="hidden" name="post_id" value="<?= $post[ 'post_id' ] ?>">
                            <input class="form__input form__input--submit" name="delete_post" type="submit" value="<?= _( 'Delete Post' ) ?>">
                        </form>
                    <?php endif; ?>
                </footer>
            </article>
        <?php endforeach; ?>
    </section>

</main>
