<?php

namespace CMS;

?>

<main id="login-reset" class="main wrapper wrapper--table">
    <div class="wrapper wrapper--table-cell">

        <?php if( isset( $_GET[ 'id' ] ) ): ?>

            <form id="reset-form" class="form box box--centered box--small" method="post">
                <div class="form__header">
                    <h1><?= _( 'Reset Password' ) ?></h1>
                    <?php Messages::printFormSuccess( 'reset-password' ); ?>
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
                    <input class="form__input form__input--hidden" name="reset_id" type="hidden" value="<?= $_GET[ 'id' ] ?>">
                    <input class="form__input form__input--submit button button--primary" name="reset-password" id="submit" type="submit" value="<?= _( 'Reset Password' ) ?>">
                </div>
            </form>

        <?php else: ?>

        <form id="reset-form" class="form box box--centered box--small" method="post">
            <div class="form__header">
                <h1><?= _( 'Reset Password' ) ?></h1>
                <?php Messages::printFormSuccess( 'request-reset' ); ?>
            </div>
            <div class="form__row">
                <label class="form__label" for="email"><?= _( 'E-Mail Address' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'email' ) ? 'form__input--error' : '' ?>" id="email" name="email" type="email" placeholder="<?= _( 'E-Mail Address' ) ?>">
                    <?php Messages::printInputErrors( 'email' ); ?>
                </div>
            </div>
            <div class="form__row">
                <input class="form__input form__input--submit button button--primary" name="request-reset" id="submit" type="submit" value="<?= _( 'Request Reset' ) ?>">
                <a class="form__anchor button button--link" href="/login"><?= _( 'Login' ) ?></a>
            </div>
        </form>

        <?php endif; ?>

    </div>
</main>
