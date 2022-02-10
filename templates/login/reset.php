<?php

namespace CMS;

?>

<main id="login-reset" class="main wrapper wrapper--table">
    <div class="wrapper wrapper--table-cell">

        <form id="login-form" class="form box box--centered box--small" method="post">
            <div class="form__header">
                <h1><?= _( 'Reset Password' ) ?></h1>
            </div>
            <div class="form__row">
                <label class="form__label" for="email"><?= _( 'E-Mail Address' ) ?></label>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--text <?= Messages::hasErrors( 'reset' ) ? 'form__input--error' : '' ?>" id="email" name="email" type="email" placeholder="<?= _( 'E-Mail Address' ) ?>">
                    <?php Messages::printInputErrors( 'reset' ); ?>
                </div>
            </div>
            <div class="form__row">
                <input class="form__input form__input--submit button button--primary" name="reset" id="submit" type="submit" value="<?= _( 'Reset Password' ) ?>">
                <a class="form__anchor button button--link" href="/login"><?= _( 'Login' ) ?></a>
            </div>
        </form>

    </div>
</main>
