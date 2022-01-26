<?php

namespace CMS;

?>

<form id="posts-form" class="form" method="post">
    <div class="form__header">
        <h2><?= _( 'Create a post' ) ?></h2>
    </div>
    <div class="form__row">
        <label class="form__label" for="title"><?= _( 'Title' ) ?></label>
        <input class="form__input form__input--text" id="title" name="title" type="text">
    </div>
    <div class="form__row">
        <label class="form__label" for="message"><?= _( 'Message' ) ?></label>
        <textarea class="form__input form__input--textarea" id="message" name="message"></textarea>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="submit" name="create_post" type="submit" value="<?= _( 'Post!' ) ?>">
    </div>

</form>
