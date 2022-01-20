<?php

namespace CMS;

?>

<form id="login-form" class="form" method="post">
    <h1><?= _( 'Login' ) ?></h1>
    <div class="form__row">
        <label class="form__label" for="username"><?= _( 'Username' ) ?></label>
        <input class="form__input form__input--text" id="username" name="username" type="text">
        <?php Errors::printInputErrors( 'username' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="password" name="password" type="password">
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="submit" type="submit" value="<?= _( 'Login' ) ?>">
    </div>
</form>
