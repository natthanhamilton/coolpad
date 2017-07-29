<?php
if (is_page('home')) {
    $navClass = 'transparent';
} else {
    $navClass = 'primary';
}
?>
<div id="sidenav" class="sidenav <?= $navClass ?>">
    <i class="fa fa-times fa-lg" id="sidenav-close"></i>
    <ul>
        <li><a href="http://coolpad.staging.wpengine.com/">Home</a></li>
        <li>Phones</li>
        <li>
            <ul>
                <li><a href="http://coolpad.staging.wpengine.com/products/conjr">coolpad conjr</li>
                <li><a href="http://coolpad.staging.wpengine.com/products/catalyst">coolpad catalyst</li>
                <li><a href="http://coolpad.staging.wpengine.com/products/tattoo">coolpad tattoo</li>
                <li><a href="http://coolpad.staging.wpengine.com/products/rogue">coolpad rogue</li>
            </ul>
        </li>
        <li><a href="http://coolpad.staging.wpengine.com/about/">About</a></li>
        <li><a href="http://coolpad.staging.wpengine.com/blogs/">News</a></li>
        <li><a href="http://support.coolpad.us/">Support</a></li>
        <li><a href="http://store.coolpad.us/">Store</a></li>
        <li>&nbsp;</li>
        <li class="bottom">&nbsp;
            <ul>
                <li style="font-weight: bold"><i class="fa fa-copyright"></i>2016 Coolpad Americas</li>
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