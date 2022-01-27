<?php

namespace CMS;

?>

<main id="register" class="main wrapper wrapper--table">
    <div class="wrapper--table-cell">

        <form id="register-form" class="form box box--centered box--small" method="post">
            <div class="form__header">
                <h1><?= _( 'Register' ) ?></h1>
            </div>
            <div class="form__row">
                <label class="form__label" for="username"><?= _( 'Username' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'username' ) ? 'form__input--error' : '' ?>" id="username" name="username" type="text" placeholder="<?= _( 'Username' ) ?>">
                    <?php Messages::printInputErrors( 'username' ); ?>
                </div>
            </div>
            <div class="form__row">
                <label class="form__label" for="email"><?= _( 'E-Mail' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'email' ) ? 'form__input--error' : '' ?>" id="email" name="email" type="email" placeholder="<?= _( 'E-Mail' ) ?>">
                    <?php Messages::printInputErrors( 'email' ); ?>
                </div>
            </div>
            <div class="form__row">
                <label class="form__label" for="password"><?= _( 'Password' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'password' ) ? 'form__input--error' : '' ?>" id="password" name="password" type="password" placeholder="<?= _( 'Password' ) ?>">
                    <?php Messages::printInputErrors( 'password' ); ?>
                </div>
            </div>
            <div class="form__row">
                <label class="form__label" for="password_repeat"><?= _( 'Repeat Password' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'password_repeat' ) ? 'form__input--error' : '' ?>" id="password_repeat" name="password_repeat" type="password" placeholder="<?= _( 'Password' ) ?>">
                    <?php Messages::printInputErrors( 'password_repeat' ); ?>
                </div>
            </div>
            <div class="form__row">
                <input class="form__input form__input--submit button button--primary" id="submit" type="submit" value="<?= _( 'Register' ) ?>">
                <a class="form__anchor button button--link" href="/login"><?= _( 'Login' ) ?></a>
            </div>
        </form>

    </div>
</main>
