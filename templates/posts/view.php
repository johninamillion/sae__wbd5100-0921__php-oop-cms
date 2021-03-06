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

    <section id="comments" class="section">
        <form id="comment-form" class="form box" method="post">
            <div class="form__header">
                <h2>Comment the Post</h2>
                <?php Messages::printFormSuccess( 'create_comment' ); ?>
            </div>
            <div class="form__row">
                <label class="form__label" for="comment">Comment</label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--hidden" name="post_id" type="hidden" value="<?= $this->Data->post[ 'post_id' ] ?>">
                    <textarea class="form__input form__input--textarea" id="comment" name="comment"></textarea>
                    <?php Messages::printInputErrors( 'comment' ); ?>
                </div>
            </div>
            <div class="form__row">
                <input class="form__input form__input--submit button button--primary" id="submit" name="create_comment" type="submit" value="<?= _( 'Create Comment' ) ?>">
            </div>
        </form>

        <ul>
            <?php foreach ( $this->Data->comments as $comment ): ?>
                <li>
                    <article class="article comment">  <div>
                            <?= _( 'Author:' ) ?> <a href="/user/profile/<?= $comment[ 'user_username' ] ?>"><?= $comment[ 'user_username' ] ?></a>
                            <?= _( 'Created:' ) ?> <span><?= $this->Data->formatDateTime( $comment[ 'created' ] ) ?></span>
                        </div>
                        <?= $comment[ 'comment' ] ?>
                        <?php if ( (int) $comment[ 'user_id' ] === Session::getValue( 'login' )[ 'id' ] || ( int ) $this->Data->post[ 'user_id' ] === Session::getValue( 'login' )[ 'id' ] ): ?>
                        <form class="form form--inline" method="post">
                            <input type="hidden" name="comment_id" value="<?= $comment[ 'comment_id' ] ?>">
                            <input class="form__input form__input--submit button button--primary" name="delete_comment" type="submit" value="<?= _( 'Delete Comment' ) ?>">
                        </form>
                        <?php endif; ?>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>

    </section>

</main>
