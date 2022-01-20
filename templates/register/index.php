<?php

namespace CMS;

?>

<form id="register-form" class="form" method="post">
    <h1><?= _( 'Register' ) ?></h1>
    <div class="form__row">
        <label class="form__label" for="username"><?= _( 'Username' ) ?></label>
        <input class="form__input form__input--text" id="username" name="username" type="text">
        <?php Errors::printInputErrors( 'username' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="email"><?= _( 'E-Mail' ) ?></label>
        <input class="form__input form__input--text" id="email" name="email" type="email">
        <?php Errors::printInputErrors( 'email' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="password" name="password" type="password">
        <?php Errors::printInputErrors( 'password' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="password_repeat"><?= _( 'Repeat Password' ) ?></label>
        <input class="form__input form__input--text" id="password_repeat" name="password_repeat" type="password">
        <?php Errors::printInputErrors( 'password_repeat' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="submit" type="submit" value="<?= _( 'Register' ) ?>">
    </div>
</form>
