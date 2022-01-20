<?php

namespace CMS;

?>

<form id="register-form" class="form" method="post">
    <div class="form__header">
        <h1><?= _( 'Register' ) ?></h1>
    </div>
    <div class="form__row">
        <label class="form__label" for="username"><?= _( 'Username' ) ?></label>
        <input class="form__input form__input--text" id="username" name="username" type="text">
        <?php Messages::printInputErrors( 'username' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="email"><?= _( 'E-Mail' ) ?></label>
        <input class="form__input form__input--text" id="email" name="email" type="email">
        <?php Messages::printInputErrors( 'email' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="password" name="password" type="password">
        <?php Messages::printInputErrors( 'password' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="password_repeat"><?= _( 'Repeat Password' ) ?></label>
        <input class="form__input form__input--text" id="password_repeat" name="password_repeat" type="password">
        <?php Messages::printInputErrors( 'password_repeat' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="submit" type="submit" value="<?= _( 'Register' ) ?>">
    </div>
</form>
