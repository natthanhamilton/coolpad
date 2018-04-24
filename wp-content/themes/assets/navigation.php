<?php
if (get_current_blog_id() == 1 AND is_page('home')) {
    $navStyle = 'nav-theme-transparent';
    $navClass = 'transparent';
} else {
    $navStyle = 'nav-theme-primary';
    $navClass = 'primary';
}
?>
<!-- Side Navigation -->
<div id="sidenav" class="sidenav <?= $navClass ?>">
    <i class="fa fa-times fa-lg" id="sidenav-close"></i>
    <ul>
        <li><a href="http://coolpad.us/">Home</a></li>
        <li>Phones</li>
        <li>
            <ul style="margin: 0 !important;">
                <li><a href="http://store.coolpad.us/product/coolpad-splatter">coolpad splatter</li>
                <li><a href="http://coolpad.us/products/defiant">coolpad defiant</li>
                <li><a href="http://coolpad.us/products/canvas">coolpad canvas</li>
                <li><a href="http://coolpad.us/products/conjr">coolpad conjr</li>
            </ul>
        </li>
        <li><a href="http://coolpad.us/about/">About</a></li>
        <li><a href="http://coolpad.us/media/">News</a></li>
        <li><a href="http://coolpad.us/blogs/">Blogs</a></li>
        <li><a href="http://store.coolpad.us/">Store</a></li>
        <li><a href="http://support.coolpad.us/">Support</a></li>
        <li><a href="http://coolpad.us/contact">Contact Us</a></li>
        <li>&nbsp;</li>
        <li class="bottom">&nbsp;
            <ul>
                <li style="font-weight: bold"><i class="fa fa-copyright"></i>2018 Coolpad Americas</li>
                <li class="social">
                    <ul>
                        <li>
                            <a target="_blank" href="https://www.facebook.com/CoolpadAmericas">
                                <icon class="fa fa-facebook fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.linkedin.com/company/coolpad">
                                <icon class="fa fa-linkedin fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.instagram.com/coolpadamericas/">
                                <icon class="fa fa-instagram fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://twitter.com/CoolpadAmericas">
                                <icon class="fa fa-twitter fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.youtube.com/user/CoolpadAmericas">
                                <icon class="fa fa-youtube fa-lg"></icon>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </li>
    </ul>
</div>
<div id="main" class="content" style="position: relative;">
    <!-- Allows mobile navigation to push page content to the side -->
    <!-- Top Navigation -->
    <nav class="navbar <?= $navStyle ?> dropdown-onhover no-fix no-border" role=navigation>
        <div class="navbar-header vertical-center">
            <!-- Side navigation toggle -->
            <i class="fa fa-bars fa-lg" id="sidenav-open"></i>
            <ul class="nav navbar-nav navbar-left">
                <li><a class="navbar-brand navbar-left" href="http://coolpad.us/">
                        <h1 style="display:none">Coolpad Americas</h1>
                        <img class="img-responsive hidden-xs hidden-sm"
                             src="<?= get_site_url().'/wp-content/themes/assets/img/Coolpad-white.png'; ?>"
                             alt="Coolpad Americas">
                        <img class="img-responsive hidden-md hidden-lg"
                             src="<?= get_site_url().'/wp-content/themes/assets/img/Coolpad.png'; ?>"
                             alt="Coolpad Americas" style="max-height: 30px">
                    </a></li>
            </ul>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                <li class="navbar-brand"><a href="http://coolpad.us/">
                        <img class="img-responsive"
                             src="<?= get_site_url().'/wp-content/themes/assets/img/Coolpad-white.png'; ?>"
                             alt="Coolpad Americas">
                    </a></li>
                <li class="dropdown-full no-shadow">
                    <a href="#" class="dropdown-toggle top-level">Phones</a>
                    <div class="dropdown-menu vertical-center">
                        <wrapper>
                            <ul>
                                <li class="col-xs-6 col-sm-2">
                                    <a href="http://store.coolpad.us/product/coolpad-splatter">
                                        <div class="image">
                                            <img class="img-responsive"
                                                 src="<?= get_site_url().'/wp-content/uploads/2018/02/Splatter.png'; ?>"
                                                 alt="Coolpad Splatter">
                                        </div>
                                        Coolpad Splatter
                                    </a>
                                </li>
                                <li class="col-xs-6 col-sm-2">
                                    <a href="http://coolpad.us/products/defiant">
                                        <div class="image">
                                            <img class="img-responsive"
                                                 src="<?= get_site_url().'/wp-content/uploads/2017/06/6666666.263.png'; ?>"
                                                 alt="Coolpad Defiant">
                                        </div>
                                        Coolpad Defiant
                                    </a>
                                </li>
                                <li class="col-xs-6 col-sm-2">
                                    <a href="http://coolpad.us/products/canvas">
                                        <div class="image">
                                            <img class="img-responsive"
                                                 src="<?= get_site_url().'/wp-content/uploads/2017/06/Canvas-Front.png'; ?>"
                                                 alt="Coolpad Canvas">
                                        </div>
                                        Coolpad Canvas
                                    </a>
                                </li>
                                <li class="col-xs-6 col-sm-2">
                                    <a href="http://coolpad.us/products/conjr">
                                        <div class="image">
                                            <img class="img-responsive"
                                                 src="<?= get_site_url().'/wp-content/themes/Coolpad/assets/images/phones/conjr/gallery/front.jpg'; ?>"
                                                 alt="Coolpad Conjr">
                                        </div>
                                        Coolpad Conjr
                                    </a>
                                </li>
                            </ul>
                        </wrapper>
                    </div>
                </li>
                <li><a href="http://coolpad.us/about">About</a></li>
                <li><a href="http://coolpad.us/media">News</a></li>
                <li><a href="http://coolpad.us/blogs">Blogs</a></li>
                <li><a href="http://store.coolpad.us/">Store</a></li>
                <li><a href="http://support.coolpad.us/">Support</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="https://store.coolpad.us/cart"><i class="fa fa-lg fa-shopping-cart"></i></a></li>
                <li class="dropdown-short all-left">
                    <a data-toggle=dropdown href="javascript:void(0);" class="dropdown-toggle top-level"><i
                                class="fa fa-lg fa-question"></i></a>
                    <ul class=dropdown-menu>
                        <li><a href="http://coolpad.us/contact">Contact Us</a></li>
                        <li class="divider no-margin"></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <script type="text/javascript">
        $('#sidenav-open').click(function () {
            $('#sidenav-close').removeClass('hidden');
            $('#sidenav-open').addClass('hidden');
            event.stopPropagation();
            document.getElementById("sidenav").style.left = "0";
            document.getElementById("main").style.left = "270px";

        });
        function sidenav_close() {
            $('#sidenav-open').removeClass('hidden');
            $('#sidenav-close').addClass('hidden');
            document.getElementById("sidenav").style.left = "-270px";
            document.getElementById("main").style.left = "0";
        }
        $('#sidenav-close').click(function () {
            sidenav_close();
        });
        $('#main').click(function () {
            sidenav_close();
        });

        if ($('#in-page-nav').length) {
            document.getElementById("sidenav-open").style.position = "fixed";
        }
    </script>
