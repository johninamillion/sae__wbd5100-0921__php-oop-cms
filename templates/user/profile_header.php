<?php

namespace CMS;

$thumbnails = unserialize( $this->Data->profile[ 'thumbnails' ] );

?>

<header id="user-profile-header" class="wrapper wrapper--outer">

    <div class="user-information">
        <img class="user-information__avatar" alt="<?= $thumbnails[ 'avatar' ][ 'name' ] ?>" src="/uploads/<?= $thumbnails[ 'avatar' ][ 'path' ] ?>"/>
    </div>

    <h1><?= $this->Data->profile[ 'username' ] ?></h1>

    <ul>
        <li>
            <span><?= sprintf( _( '%s Posts' ), $this->Data->profile[ 'posts' ] ) ?></span>
        </li>
        <li>
            <span><?= sprintf( _( '%s Likes' ), $this->Data->profile[ 'likes' ] ) ?></span>
        </li>
        <li>
            <span><?= sprintf( _( '%s Comments' ), $this->Data->profile[ 'comments' ] ) ?></span>
        </li>
    </ul>

    <?php if( Session::getValue( 'login' )[ 'username' ] === $this->Data->profile[ 'username' ] ): ?>
        <a class="button button--primary" href="/user/settings"><?= _( 'Settings' ) ?></a>
    <?php else: ?>
        <form class="form form--inline" method="post">
            <input type="hidden" name="profile_id" value="<?= $this->Data->profile[ 'id' ] ?>">
            <input class="form__input form__input--submit button button--primary" type="submit" value="<?= _( 'Follow' ) ?>">
        </form>
    <?php endif; ?>

</header>
