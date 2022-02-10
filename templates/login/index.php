<?php

namespace CMS;

?>

<main id="login" class="main wrapper wrapper--table">
    <div class="wrapper wrapper--table-cell">

        <form id="login-form" class="form box box--centered box--small" method="post">
            <div class="form__header">
                <h1><?= _( 'Login' ) ?></h1>
                <?php Messages::printRedirectMessage( [ 'logout' => _( 'Welcome Back!' ), 'register' => _( 'Have Fun!' ), 'unauthorized' => _( 'Login to get access!' ), 'verification' => _( 'Registration completed' ), 'reset' => _( 'Password reset' ) ] ); ?>
            </div>
            <div class="form__row">
                <label class="form__label" for="username"><?= _( 'Username' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'login' ) ? 'form__input--error' : '' ?>" id="username" name="username" type="text" placeholder="<?= _( 'Username' ) ?>">
                    <?php Messages::printInputErrors( 'login' ); ?>
                </div>
            </div>
            <div class="form__row">
                <label class="form__label" for="password"><?= _( 'Password' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text" id="password" name="password" type="password" placeholder="<?= _( 'Password' ) ?>">
                </div>
            </div>
            <div class="form__row">
                <input class="form__input form__input--submit button button--primary" id="submit" type="submit" value="<?= _( 'Login' ) ?>">
                <a class="form__anchor button button--link" href="/register"><?= _( 'Register' ) ?></a>
            </div>
        </form>

    </div>
</main>
