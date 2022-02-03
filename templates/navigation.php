
<header id="masthead">
    <div id="masthead__inner" class="wrapper wrapper--outer">

        <nav id="masthead__navigation" class="navigation navigation--inline">
            <ul class="navigation__list">
                <li class="navigation__list-item"><a class="navigation__anchor" href="/posts"><?= _( 'Posts' ) ?></a></li>
                <li class="navigation__list-item"><a class="navigation__anchor" href="/user/settings"><?= _( 'Settings' ) ?></a></li>
                <li class="navigation__list-item"><a class="navigation__anchor" href="/user/profile"><?= _( 'Profile' ) ?></a></li>
                <li class="navigation__list-item"><a class="navigation__anchor" href="/logout"><?= _( 'Logout' ) ?></a></li>
            </ul>
            <form class="form form--inline" action="/search" method="get">
                <div class="form__row">
                    <input name="keyword" type="text" placeholder="<?= _( 'Search' ) ?>">
                </div>
            </form>
        </nav>

    </div>
</header>
