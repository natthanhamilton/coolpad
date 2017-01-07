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
<div id="frame-container">
    <div id="frame" style="width: 225px; margin: auto">
        <div id="phone-frame"
             style='background-image: url("<?php echo get_template_directory_uri() . '/assets/images/phones/conjr/PhoneFrame.png'; ?>")'>
            <img id="screen"
                 src="<?php echo get_template_directory_uri() . '/assets/images/phones/conjr/screens/ISO.jpg'; ?>">
        </div>
    </div>
</div>
<div id="fullpage" class="phone-conjr">
    <div id="cover-trigger" style="position: absolute; top:300px;"></div>
    <div id="cover-bg" class="section">
        <div id="cover">
            <div style="background: rgba(0,0,0,0.3);">
                <div class="container vertical-center fullvh">
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="container" id="cover">
            <div class="row">
                <div class="col-sm-12 vertical-center">
                    <wrapper style="opacity: 1;">
                        <img class="img-responsive" src="http://localhost/coolpad/wp-content/uploads/2016/12/UIBANNER.png"
                             style="display: block; max-height: 500px">
                        <h3 class="text-center">Cool UI 8.0</h3>
                        <p class="text-center">
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
        <div class="container">
            <div class="row vertical">
                <wrapper>
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

        var centerPhone = TweenMax.to('#frame', 0.5, {
            top: '50%',
        });
        var phone = new ScrollMagic.Scene({
            triggerElement: '#multitasking',
        }).setTween(centerPhone).addTo(scrollMagic);

        /* Cover */
        var tweenBackground = TweenMax.to('#cover', 0.5, {
            opacity: 0
        });
        var background = new ScrollMagic.Scene({
            triggerElement: '#cover-trigger',
            duration: 1000
        }).setTween(tweenBackground)
            .addTo(scrollMagic);

        // Playful - Show Image
        var playfulTween = TweenMax.to('#screen', 0, {
            src: '<?php echo get_template_directory_uri() . "/assets/images/phones/conjr/screens/WhiteBalance.jpg"; ?>',
        });
        var playful = new ScrollMagic.Scene({
            triggerElement: '#unlock'
        }).setTween(playfulTween).addTo(scrollMagic);
    });
    $(document).ready(function () {
        $('#fullpage').fullpage({
            scrollBar: true
        });
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