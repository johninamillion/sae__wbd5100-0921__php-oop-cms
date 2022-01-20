<?php

namespace CMS;

?>

<h1><?= _( 'User Settings' ) ?></h1>

<form id="user-settings-password-form-" class="form" method="post">
    <h2><?= _( 'Update user password' ) ?></h2>
    <div class="form__row">
        <label class="form__label" for="update-password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="update-password" name="password" type="password">
        <?php Errors::printInputErrors( 'login' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-new-password"><?= _( 'New password' ) ?></label>
        <input class="form__input form__input--text" id="update-new-password" name="new_password" type="password">
        <?php Errors::printInputErrors( 'password' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-new-password-repeat"><?= _( 'Repeat new password' ) ?></label>
        <input class="form__input form__input--text" id="update-new-password-repeat" name="new_password_repeat" type="password">
        <?php Errors::printInputErrors( 'password_repeat' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="update-submit" type="submit" name="update" value="<?= _( 'Update password' ) ?>">
    </div>
</form>

<form id="user-settings-delete-form" class="form" method="post">
    <h2><?= _( 'Delete user account' ) ?></h2>
    <div class="form__row">
        <label class="form__label" for="delete-password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="delete-password" name="password" type="password">
        <?php Errors::printInputErrors( 'login' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="delete-submit" type="submit" name="delete" value="<?= _( 'Delete user account' ) ?>">
    </div>
</form>
