<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php if (is_category()) {
            echo 'Category Archive for &quot;';
            single_cat_title();
            echo '&quot; | ';
            bloginfo('name');
        } elseif (is_tag()) {
            echo 'Tag Archive for &quot;';
            single_tag_title();
            echo '&quot; | ';
            bloginfo('name');
        } elseif (is_archive()) {
            wp_title('');
            echo ' Archive | ';
            bloginfo('name');
        } elseif (is_search()) {
            echo 'Search for &quot;' . wp_specialchars($s) . '&quot; | ';
            bloginfo('name');
        } elseif (is_home()) {
            bloginfo('name');
            echo ' | ';
            bloginfo('description');
        } elseif (is_404()) {
            echo 'Error 404 Not Found | ';
            bloginfo('name');
        } elseif (is_single()) {
            wp_title('');
        } else {
            echo wp_title('');
            echo ' | ';
            bloginfo('name');
        } ?></title>
    <meta name="description" content="<?php wp_title('');
    echo ' | ';
    bloginfo('description'); ?>"/>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="http://res.cloudinary.com/coolpad/image/upload/v1464995065/favicon.jpg" type="image/x-icon"/>
    <?php wp_head(); ?>
    <link rel="stylesheet" type="text/css" media="all"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="all"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/2.23.2/mediaelementplayer.min.css"/>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.min.css"/>
    <link rel="stylesheet" type="text/css" media="all"
          href="<?php echo get_template_directory_uri() . '/assets/css/style.css'; ?>"/>
    <link rel="stylesheet" type="text/css" media="all"
          href="<?php echo get_template_directory_uri() . '/assets/css/conjr-ui.css'; ?>"/>
</head>
<body <?php body_class(); ?> id="skrollr-body" data-spy="scroll" data-target=".scrollspy">
<div id="fullpage" class="phone-conjr">
    <div class="section" id="cover">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 vertical-center" style="height: 100%">
                    <wrapper style="opacity: 1;">
                        <img class="img-responsive"
                             src="http://localhost/coolpad/wp-content/uploads/2016/12/UIBANNER.png"
                             style="display: block; max-height: 500px">
                        <h3 class="text-center">Cool UI 8.0</h3>
                        <p class="text-center section-padding">
                            Cool UI brings you the most customizable experience of any Android phone with features
                            like: Multi-Finger Unlock, Call Recording, Multi-Screen Capture, Smart gestures, C
                            Button Navigation, Screen Recording, and so much more. Cool UI is our most advanced in
                            photography as well allowing you to: micro focus(up to 3in away), beauty filter, and
                            even an age guesser. Now with even faster updates that are inspired by you!
                        </p>
                    </wrapper>
                </div>
            </div>
        </div>
    </div>
    <div class="section" id="unlock">
        <div class="container" id="phone-container">
            <div class="row vertical">
                <div class="col-xs-12 col-sm-8">
                <img id="phone" src="http://localhost/coolpad/wp-content/themes/coolpad/assets/images/phones/conjr/ui/CoolUIFingerPrint.png" style="margin: auto">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row vertical">
                <wrapper>
                    <div class="col-sm-6">
                        <img class="mobile-phone img-responsive"
                             src="http://localhost/coolpad/wp-content/uploads/2016/12/cooluifingerprint-1.png">
                    </div>
                    <div class="col-sm-6" id="unlock-content">
                        <h3>Multi Finger Unlock</h3>
                        <p>
                            Unlock your coolpad conjr with up to 5 differnt fingerprints. Immediate recogniction allows
                            you
                            to quick open apps and unlock your phone. Protect your personal information, family photos,
                            banking, social media and so much more with a signature that is unique to you.
                        </p>
                    </div>
                </wrapper>
            </div>
        </div>
    </div>
    <div class="section" id="gestures">
        <div class="container">
            <div class="row vertical">
                <wrapper>
                    <div class="col-sm-6">
                        <img class="mobile-phone img-responsive"
                             src="http://localhost/coolpad/wp-content/themes/coolpad/assets/images/phones/conjr/ui/cooluismart-gesture.png">
                    </div>
                    <div class="col-sm-6" id="gestures-content">
                        <h3>Smart Gestures</h3>
                        <p>
                            Our new Smart Gestures allow you to launch apps from stand-by. Program C for phone dialpad,
                            E
                            for your webrowser, M for Music and so many more options.
                        </p>
                    </div>
                </wrapper>
            </div>
        </div>
    </div>
    <div class="section" id="recording">
        <div class="container">
            <div class="row vertical">
                <wrapper>
                    <div class="col-sm-6">
                        <img class="mobile-phone img-responsive"
                             src="http://localhost/coolpad/wp-content/themes/coolpad/assets/images/phones/conjr/ui/cooluiscreenrecording.png">
                    </div>
                    <div class="col-sm-6" id="recording-content">
                        <h3>Screen Recording</h3>
                        <p>
                            Cool UI 8.0 brings you the most seemless screen recording on the market. Now you can access
                            from
                            you control center and immediately with one button start screen recording. With the conjr
                            you
                            can screen record in High Definiton at 60fps.
                        </p>
                    </div>
                </wrapper>
            </div>
        </div>
    </div>
    <div class="section" id="button">
        <div class="container">
            <div class="row vertical">
                <wrapper>
                    <div class="col-sm-6">
                        <img class="mobile-phone img-responsive"
                             src="http://localhost/coolpad/wp-content/themes/coolpad/assets/images/phones/conjr/ui/CoolUICButton.png">
                    </div>
                    <div class="col-sm-6" id="button-content">
                        <h3>C Button</h3>
                        <p>
                            With larger and larger phone screens we still have the need to try and control our phones
                            with
                            one hand. Our new C Button allows you to create a custom menu that appears on the click of a
                            button. You can place C Button anywhere you want on your screen and it will fade away so
                            it’s
                            not in the way of your work.
                        </p>
                    </div>
                </wrapper>
            </div>
        </div>
    </div>
    <div class="section" id="video">
        <div class="container">
            <div class="row vertical">
                <wrapper>
                    <div class="col-sm-6">
                        <img class="mobile-phone img-responsive"
                             src="http://localhost/coolpad/wp-content/themes/coolpad/assets/images/phones/conjr/ui/cooluivideoapp.png">
                    </div>
                    <div class="col-sm-6" id="video-content">
                        <h3>Video App</h3>
                        <p>
                            Cool UI’s Video App features options like: Picture in a Picture so you can watch all your
                            favorite movies while texting with your friends, Cast Screen, you can now broadcast what you
                            are
                            watching straight to your TV, along with Automatic Subtitles.
                        </p>
                    </div>
                </wrapper>
            </div>
        </div>
    </div>
    <div class="section" id="screenshot">
        <div class="container">
            <div class="row vertical">
                <wrapper>
                    <div class="col-sm-6">
                        <img class="mobile-phone img-responsive"
                             src="http://localhost/coolpad/wp-content/themes/coolpad/assets/images/phones/conjr/ui/cooluiscreenshot.png">
                    </div>
                    <div class="col-sm-6" id="screenshot-content">
                        <h3>Scrolling Screen Shot</h3>
                        <p>
                            Expand upon the screen you are trying to capture. If you have ever tried to take a screen
                            shot
                            of something bigger than can fit on one page this is the feeature you have been looking for.
                            With scrolling sreen shot mutli-page screenshots are now possible.
                        </p>
                    </div>
                </wrapper>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var scrollMagic = new ScrollMagic.Controller();

        /* don't let phone show on cover */
        var navTween = TweenMax.to('#phone-container', 0, {
            display: 'block'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#unlock'
        }).setTween(navTween).addTo(scrollMagic);

        /* In page nav */
        var navTween = TweenMax.to('#in-page-nav', 0, {
            position: 'fixed',
            top: 0
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#unlock'
        }).setTween(navTween).addTo(scrollMagic);

        /*
         Images
         */

        //unlock's content
        var navTween = TweenMax.to('#phone', 0, {
            src: 'http://localhost/coolpad/wp-content/uploads/2016/12/cooluifingerprint-1.png'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#unlock'
        }).setTween(navTween).addTo(scrollMagic);

        //gestures's content
        var navTween = TweenMax.to('#phone', 0, {
            src: 'http://localhost/coolpad/wp-content/uploads/2016/12/cooluismart-gesture.png'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#gestures'
        }).setTween(navTween).addTo(scrollMagic);

        //gestures's content
        var navTween = TweenMax.to('#phone', 0, {
            src: 'http://localhost/coolpad/wp-content/uploads/2016/12/cooluiscreenrecording.png'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#recording'
        }).setTween(navTween).addTo(scrollMagic);

        //gestures's content
        var navTween = TweenMax.to('#phone', 0, {
            src: 'http://localhost/coolpad/wp-content/uploads/2016/12/cooluicbutton-1.png'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#button'
        }).setTween(navTween).addTo(scrollMagic);

        //gestures's content
        var navTween = TweenMax.to('#phone', 0, {
            src: 'http://localhost/coolpad/wp-content/uploads/2016/12/cooluivideoapp.png'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#video'
        }).setTween(navTween).addTo(scrollMagic);

        //gestures's content
        var navTween = TweenMax.to('#phone', 0, {
            src: 'http://localhost/coolpad/wp-content/uploads/2016/12/cooluiscreenshot.png'
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#screenshot'
        }).setTween(navTween).addTo(scrollMagic);

        /*
         Content
         */

        //unlock's content
        var navTween = TweenMax.to('#unlock-content', 3, {
            opacity: 1
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#unlock'
        }).setTween(navTween).addTo(scrollMagic);

        //gestures's content
        var navTween = TweenMax.to('#gestures-content', 3, {
            opacity: 1
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#gestures'
        }).setTween(navTween).addTo(scrollMagic);

        /* recording content */
        var navTween = TweenMax.to('#recording-content', 3, {
            opacity: 1
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#recording'
        }).setTween(navTween).addTo(scrollMagic);

        //button's content
        var navTween = TweenMax.to('#button-content', 3, {
            opacity: 1
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#button'
        }).setTween(navTween).addTo(scrollMagic);

        //video's content
        var navTween = TweenMax.to('#video-content', 3, {
            opacity: 1
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#video'
        }).setTween(navTween).addTo(scrollMagic);

        //screenshot's content
        var navTween = TweenMax.to('#screenshot-content', 3, {
            opacity: 1
        });
        var nav = new ScrollMagic.Scene({
            triggerElement: '#screenshot'
        }).setTween(navTween).addTo(scrollMagic);
    });
</script>
<script type="text/javascript">

    $(document).ready(function () {
        $('#fullpage').fullpage({
            scrollBar: true
        });
    });
</script>
</body>
</html>