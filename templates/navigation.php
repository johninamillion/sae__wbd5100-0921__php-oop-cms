
<header id="masthead">
    <div id="masthead__inner" class="wrapper wrapper--outer">

        <nav id="masthead__navigation" class="navigation navigation--inline">
            <ul class="navigation__list">
                <li class="navigation__list-item"><a class="navigation__anchor" href="/posts"><?= _( 'Posts' ) ?></a></li>
                <li class="navigation__list-item"><a class="navigation__anchor" href="/user/settings"><?= _( 'Settings' ) ?></a></li>
                <li class="navigation__list-item"><a class="navigation__anchor" href="/user/profile"><?= _( 'Profile' ) ?></a></li>
                <li class="navigation__list-item"><a class="navigation__anchor" href="/logout"><?= _( 'Logout' ) ?></a></li>
            </ul>

            <form id="search-form" class="form form--inline" action="/search" method="get">
                <input class="form__input form__input--text" name="keyword" type="text" placeholder="<?= _( 'Search' ) ?>">
            </form><!-- /#search-form -->

        </nav><!-- /#masthead__navigation -->

    </div><!-- /#masthead__inner -->
</header><!-- /#masthead -->
