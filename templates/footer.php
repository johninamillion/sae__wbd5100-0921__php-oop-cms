
        <footer id="colophon">
            <p id="copyright"><?= sprintf( _( '&copy; Copyright %s' ), date( 'Y' ) ) ?> - SAE Institute</p>
            <nav class="navigation navigation--inline">
                <ul class="navigation__list">
                    <li class="navigation__list-item"><a class="navigation__anchor" href="/"><?= _( 'Imprint' ) ?></a></li>
                    <li class="navigation__list-item"><a class="navigation__anchor" href="/"><?= _( 'Privacy' ) ?></a></li>
                </ul>
            </nav>
        </footer>

        <?php $this->Scripts->printScripts(); ?>

    </body>
</html>