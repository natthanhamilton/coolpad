
<div class="pure-container" data-effect="pure-effect-push">

    <!-- Side navigation toggle -->
    <input type="checkbox" id="pure-toggle-left" class="pure-toggle" data-toggle="left"/>
    <label for="pure-toggle-left" id="sidebar-icon">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </label>

    <!-- side navigation -->
    <nav class="pure-drawer sidenav" data-position="left">
    <i class="fa fa-times fa-lg" id="sidenav-close"></i>
    <ul>
        <li><a href="{{ path('pioc_web_homepage') }}">Home</a></li>
        <li><a href="{{ path('page_wall-decor') }}">Canvas Product</a></li>
        <li><a href="{{ path('page_custom-photo-gifts') }}">Gifts</a></li>
        <li><a href="{{ path('page_sports_home') }}">Sports</a></li>
        <li><a href="{{ path('page_enhancements') }}">Options</a></li>
        <li><a href="{{ path('image_bank_homepage') }}">Find Your Inspiration</a></li>
        <li><a href="http://store.pictureitoncanvas.com/mm5/merchant.mvc?Screen=BASK">Checkout</a></li>
        <li>&nbsp;</li>
        <li class="help">
            <ul>
                <li><a href="{{ path('page_how-to-order') }}"><i class="fa fa-picture-o"></i> How to Order</a></li>
                <li><a href="http://store.pictureitoncanvas.com/mm5/OrderStatus/OrderStatus.php"><i
                                class="fa fa-truck"></i> Order Status</a></li>
                <li><a href="{{ path('page_shipping-returns') }}"><i class="fa fa-calendar"></i> Shipping & Returns</a>
                </li>
                <li><a href="{{ path('page_faq') }}"><i class="fa fa-list-ul"></i> FAQ</a></li>
                <li><a href="{{ path('page_contact') }}"><i class="fa fa-comments-o"></i> Contact us</a></li>
            </ul>
        </li>
        <li>&nbsp;</li>
        <li class="bottom">&nbsp;
            <ul>
                <li style="font-weight: bold"><i class="fa fa-copyright"></i>2016 Coolpad Americas</li>
                <li class="social">
                    <ul>
                        <li>
                            <a target="_blank" href="https://www.facebook.com/piocinc">
                                <icon class="fa fa-facebook fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://twitter.com/PIOCanvas">
                                <icon class="fa fa-twitter fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.pinterest.com/piocanvas/">
                                <icon class="fa fa-pinterest fa-lg"></icon>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.instagram.com/PictureItOn/">
                                <icon class="fa fa-instagram fa-lg"></icon>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </li>
    </ul>
    </nav>

    <!-- push container -->
    <div class="pure-pusher-container">
        <div class="pure-pusher">