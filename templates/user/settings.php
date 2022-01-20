<?php

namespace CMS;

?>

<h1><?= _( 'User settings' ) ?></h1>

<form id="user-settings-update-username-form" class="form" method="post">
    <div class="form__header">
        <h2><?= _( 'Update username' ) ?></h2>
        <?php Messages::printFormSuccess( 'update_username' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-username"><?= _( 'Username' ) ?></label>
        <input class="form__input form__input--text" id="update-username" name="new_username" type="text">
        <?php Messages::printInputErrors( 'new_username' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="update-username-submit" name="update_username" type="submit" value="<?= _( 'Update Username' ) ?>">
    </div>
</form>

<form id="user-settings-update-email-form" class="form" method="post">
    <div class="form__header">
        <h2><?= _( 'Update email' ) ?></h2>
        <?php Messages::printFormSuccess( 'update_email' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-email"><?= _( 'Email' ) ?></label>
        <input class="form__input form__input--text" id="update-email" name="new_email" type="email">
        <?php Messages::printInputErrors( 'new_email' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="update-email-submit" name="update_email" type="submit" value="<?= _( 'Update Email' ) ?>">
    </div>
</form>

<form id="user-settings-update-password-form" class="form" method="post">
    <div class="form__header">
        <h2><?= _( 'Update user password' ) ?></h2>
        <?php Messages::printFormSuccess( 'update_password' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="update-password" name="password" type="password">
        <?php Messages::printInputErrors( 'update_password' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-new-password"><?= _( 'New password' ) ?></label>
        <input class="form__input form__input--text" id="update-new-password" name="new_password" type="password">
        <?php Messages::printInputErrors( 'new_password' ); ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="update-new-password-repeat"><?= _( 'Repeat new password' ) ?></label>
        <input class="form__input form__input--text" id="update-new-password-repeat" name="new_password_repeat" type="password">
        <?php Messages::printInputErrors( 'new_password_repeat' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="update-password-submit" name="update_password" type="submit" value="<?= _( 'Update password' ) ?>">
    </div>
</form>

<form id="user-settings-delete-form" class="form" method="post">
    <div class="form__header">
        <h2><?= _( 'Delete user account' ) ?></h2>
    </div>
    <div class="form__row">
        <label class="form__label" for="delete-password"><?= _( 'Password' ) ?></label>
        <input class="form__input form__input--text" id="delete-password" name="password" type="password">
        <?php Messages::printInputErrors( 'delete' ); ?>
    </div>
    <div class="form__row">
        <input class="form__input form__input--submit" id="delete-submit" name="delete" type="submit" value="<?= _( 'Delete user account' ) ?>">
    </div>
</form>
