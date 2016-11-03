<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.min.css"/>
<link rel="stylesheet" type="text/css" media="all"
      href="<?php echo get_template_directory_uri() . '/assets/css/rfp-alchemy.css'; ?>"/>
<div id="frame-container">
    <div id="frame" style="width: 225px; margin: auto">
        <div id="phone-frame"
             style='background-image: url("<?php echo get_template_directory_uri() . '/rfp/alchemy/empty.png'; ?>")'>
            <img id="screen"
                 src="<?php echo get_template_directory_uri() . '/rfp/alchemy/nougat.png'; ?>">
            <!--<img id="screen"
                 src="<?php // echo get_template_directory_uri() . '/rfp/alchemy/camera.png'; ?>">-->
            <video autoplay="autoplay" loop id="video" src=""></video>
            <img class="animations" id="print1"
                 src="<?php echo get_template_directory_uri() . '/rfp/alchemy/map.png'; ?>">
            <img class="animations" id="print2"
                 src="<?php echo get_template_directory_uri() . '/rfp/alchemy/contacts.png'; ?>">
            <img class="animations" id="print3"
                 src="<?php echo get_template_directory_uri() . '/rfp/alchemy/facebook.png'; ?>">
        </div>
    </div>
</div>
<div id="fullpage">
    <div id="cover-trigger" style="position: absolute; top:300px;"></div>

    <div id="cover-bg" class="section">
        <div id="cover">
            <!--
            <div class="vertical-center"
                 style="position: absolute;width: 100%;color: #fff;font-size: 60px;height: 100vh;">
                <div>
                    <h1 style="font-size: 8rem; color: #fff">Coolpad Alchemy</h1>
                </div>
            </div>
            -->
            <div style="background: rgba(0,0,0,0.3);">
                <div class="container vertical-center fullvh">
                    <div>
                        <div class="row" style="height: 20%">
                            <div class="col-xs-1 vertical" style="height: 115px">
                                <div class="pulse_holder">
                                    <div class="pulse_marker">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <p>
                                    The Milliennials<br/>
                                    Inspired by being free
                                </p>
                            </div>
                            <div class="col-sm-4 col-sm-offset-2">
                                <p>
                                    Frequent to heavy internet users
                                </p>
                                <div class="pulse_holder">
                                    <div class="pulse_marker">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="height: 20%">
                            <div class="col-sm-12 text-center">
                                <h1 style="font-size: 8rem; color: #fff">Coolpad Alchemy</h1>
                            </div>
                        </div>
                        <div class="row" style="height: 20%">
                            <div class="col-sm-6">
                                <p>
                                    Social media ninjas
                                </p>
                                <div class="pulse_holder">
                                    <div class="pulse_marker" style="float: left;">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pulse_holder">
                                    <div class="pulse_marker" style="float: right;">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                                <p>
                                    Budget conscious and lovers of all things functional
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh" id="multitasking"
         style="background: url('<?php echo get_template_directory_uri() . '/rfp/alchemy/multitasking.jpg'; ?>') center center no-repeat; background-size: cover">
        <div class="container">
            <div class="row half-height bottom">
                <div class="col-sm-3 col-sm-offset-8">
                    <h4>Multi-task Window</h4>
                    <h4>Do more</h4>
                    <h4>See more</h4>
                    <h4>Laugh more</h4>
                    <h4>Enjoy more</h4>
                </div>
            </div>
            <div class="row half-height vertical">
                <div class="col-sm-3 col-sm-offset-1 text-right">
                    <h2>The Multi You</h2>
                    <h3>6" FHD Display</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh" id="fingerprint"
         style="background: url('http://i01.appmifile.com/webfile/globalimg/en/goods/mimax/experience-film-01.jpg?v=20160629') center center no-repeat; background-size: cover">
        <div class="container">
            <div class="row vertical">
                <div class="col-sm-4">
                    <div class="pull-right" style="width: 225px;
                        background: url('<?php echo get_template_directory_uri() . '/rfp/alchemy/back.png'; ?>') center center no-repeat;
                        background-size: cover;
                        height: 450px">
                        <div class="pulse_holder" id="touchPrint">
                            <div class="pulse_finger">
                                <div class="pulse_rays"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-sm-offset-4">
                    <h2>The Free You</h2>
                    <h3>Faster Multi-Finger Unlock</h3>
                    <p>Faster than iPhone finger unlock with instant access to 5 of your favorite apps with the power
                        of
                        your fingertips</p>
                    <div class="col-sm-4 text-center">
                        <img class="img-responsive"
                             src="<?php echo get_template_directory_uri() . '/rfp/alchemy/fingerprint-black.png'; ?>">
                        Open Maps
                    </div>
                    <div class="col-sm-4 text-center">
                        <img class="img-responsive"
                             src="<?php echo get_template_directory_uri() . '/rfp/alchemy/fingerprint-black.png'; ?>">
                        Open Contact
                    </div>
                    <div class="col-sm-4 text-center">
                        <img class="img-responsive"
                             src="<?php echo get_template_directory_uri() . '/rfp/alchemy/fingerprint-black.png'; ?>">
                        Open Facebook
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh" id="playful"
         style="background: url('http://i01.appmifile.com/webfile/globalimg/en/goods/mimax/experience-gps-01.jpg?v=20160629') center center no-repeat; background-size: cover">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-sm-offset-2 vertical">
                    <div>
                        <p style="margin-bottom: 50px;">
                            Playful beauty shots with age recognition<br/>
                            Low night mode for better night shots
                        </p>
                        <h2>The Playful You</h2>
                        <h3>13MP + 13MP Dual Camera</h3>
                    </div>
                </div>
                <div class="col-sm-4 col-sm-offset-3 pull-right bottom">
                    <div>
                        <img class="img-responsive"
                             src="<?php echo get_template_directory_uri() . '/rfp/alchemy/angled.png'; ?>"
                             style="height: 450px;
    position: relative;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh" id="lifestyle">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div style="margin-top: 10%;">
                        <h2 style="font-size: 6rem; color: #fff">The Ultimate Dream You</h2>
                        <h3 style="font-size: 4rem">All in one</h3>
                    </div>
                </div>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
<script type="text/javascript">
    $(function () {
        var scrollMagic = new ScrollMagic.Controller();

        /* Scroll the phone
         var screen = new ScrollMagic.Scene({triggerElement: "#frame-container"})
         .setPin("#frame-container")
         .addTo(scrollMagic);*/
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
        })
            .setTween(tweenBackground)
            .addTo(scrollMagic);


        /*

         MULTITASKING

         */


        // Hide
        var multitaskingHideScreen = TweenMax.to('#screen', 0, {
            opacity: 0
        });
        var multitaskingHide1 = new ScrollMagic.Scene({
            triggerElement: '#multitasking'
        })
            .setTween(multitaskingHideScreen)
            .addTo(scrollMagic);
        var multitaskingHideprint1 = TweenMax.to('#print1', 0, {
            opacity: 0
        });
        var multitaskingHide2 = new ScrollMagic.Scene({
            triggerElement: '#multitasking'
        })
            .setTween(multitaskingHideprint1)
            .addTo(scrollMagic);
        var multitaskingHideprint2 = TweenMax.to('#print2', 0, {
            opacity: 0
        });
        var multitaskingHide3 = new ScrollMagic.Scene({
            triggerElement: '#multitasking'
        })
            .setTween(multitaskingHideprint2)
            .addTo(scrollMagic);
        var multitaskingHideprint3 = TweenMax.to('#print3', 0, {
            opacity: 0
        });
        var multitaskingHide4 = new ScrollMagic.Scene({
            triggerElement: '#multitasking'
        })
            .setTween(multitaskingHideprint3)
            .addTo(scrollMagic);
        // Multi Tasking - Show Video
        var multitaskingvideoTween = TweenMax.to('#video', 0, {
            src: '<?php echo get_template_directory_uri() . '/rfp/alchemy/splitscreen.mp4'; ?>',
            opacity: 1
        });
        var multitaskingVideo = new ScrollMagic.Scene({
            triggerElement: '#multitasking'
        }).setTween(multitaskingvideoTween).addTo(scrollMagic);


        /*

         FINGERPRINT

         */

        // Hide
        var fingerprintHideDefaultImage = TweenMax.to('#default', 0, {
            display: 'none'
        });
        var FingerprintHideDefault = new ScrollMagic.Scene({
            triggerElement: '#fingerprint'
        }).setTween(fingerprintHideDefaultImage).addTo(scrollMagic);
        var fingerprintHideVideoLayer = TweenMax.to('#video', 0, {
            opacity: 0
        });
        var FingerprintHideVideo = new ScrollMagic.Scene({
            triggerElement: '#fingerprint'
        }).setTween(fingerprintHideVideoLayer).addTo(scrollMagic);
        // Animation
        var fingerPrintAnimation = new TimelineMax({
            delay: 0,
            repeat: 3
        });
        fingerPrintAnimation.from("#print1", 2, {opacity: 1})
            .to("#print1", 0, {opacity: 0})
            .to("#print2", 2, {opacity: 1})
            .to("#print2", 0, {opacity: 0})
            .to("#print3", 2, {opacity: 1})
            .to("#print3", 0, {opacity: 0});
        var fingerprint = new ScrollMagic.Scene({
            triggerElement: '#fingerprint'
        }).setTween(fingerPrintAnimation).addTo(scrollMagic);


        /*

         PLAYFUL

         */


        // Hide
        var PlayfulHideVideo = TweenMax.to('#video', 0, {
            opacity: 0
        });
        var PlayfulHide1 = new ScrollMagic.Scene({
            triggerElement: '#playful'
        })
            .setTween(PlayfulHideVideo)
            .addTo(scrollMagic);
        var PlayfulHideprint1 = TweenMax.to('#print1', 0, {
            opacity: 0
        });
        var PlayfulHide2 = new ScrollMagic.Scene({
            triggerElement: '#playful'
        })
            .setTween(PlayfulHideprint1)
            .addTo(scrollMagic);
        var PlayfulHideprint2 = TweenMax.to('#print2', 0, {
            opacity: 0
        });
        var PlayfulHide3 = new ScrollMagic.Scene({
            triggerElement: '#playful'
        })
            .setTween(PlayfulHideprint2)
            .addTo(scrollMagic);
        var PlayfulHideprint3 = TweenMax.to('#print3', 0, {
            opacity: 0
        });
        var PlayfulHide4 = new ScrollMagic.Scene({
            triggerElement: '#playful'
        })
            .setTween(PlayfulHideprint3)
            .addTo(scrollMagic);
        // Playful - Show Image
        var playfulTween = TweenMax.to('#screen', 0, {
            src: '<?php echo get_template_directory_uri() . "/rfp/alchemy/camera.png"; ?>',
            opacity: 1
        });
        var playful = new ScrollMagic.Scene({
            triggerElement: '#playful'
        }).setTween(playfulTween).addTo(scrollMagic);


        /*

         LIFESTYLE

         */


        // Hide
        var LifestyleHideScreenLayer = TweenMax.to('#screen', 0, {
            opacity: 0
        });
        var lifestyleHide1 = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(LifestyleHideScreenLayer).addTo(scrollMagic);
        var LifestyleHideprint1Layer = TweenMax.to('#print1', 0, {
            display: 'none'
        });
        var lifestyleHide2 = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(LifestyleHideprint1Layer).addTo(scrollMagic);
        var LifestyleHideprint2Layer = TweenMax.to('#print2', 0, {
            display: 'none'
        });
        var lifestyleHide3 = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(LifestyleHideprint2Layer).addTo(scrollMagic);
        var LifestyleHideprint3Layer = TweenMax.to('#print3', 0, {
            display: 'none'
        });
        var lifestyleHide4 = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(LifestyleHideprint3Layer).addTo(scrollMagic);

        // lifestyle - Show video
        var lifestyleTween = TweenMax.to('#video', 0, {
            src: '<?php echo get_template_directory_uri() . "/rfp/alchemy/teaser.mp4"; ?>',
            opacity: 1,
            rotation: 270,
            height: 301,
            top: 171,
            left: -103
        });
        var lifestyle = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(lifestyleTween).addTo(scrollMagic);

        // Playful - Rotate frame and change width
        var lifestyleRotateTween = TweenMax.to('#frame', 0, {
            width: 325
        });
        var lifestyleRotate = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(lifestyleRotateTween).addTo(scrollMagic);

        // Playful - increase phone-frame size
        var lifestylePhoneFrameTween = TweenMax.to('#phone-frame', 0, {
            rotation: 90,
            height: 650,
            transition: '1s ease'
        });
        var lifestylePhoneFrame = new ScrollMagic.Scene({
            triggerElement: '#lifestyle'
        }).setTween(lifestylePhoneFrameTween).addTo(scrollMagic);


        /*
         $('#fullpage').fullpage({
         css3: false,
         scrollingSpeed: 1000
         });

         var contentOffset = 0; // offsetcache
         // function to update the current scroll offset
         var updateContentOffset = function () {
         var curOffset = $("#fullpage").offset().top;
         if (curOffset != contentOffset) {
         contentOffset = curOffset;
         scrollMagic.update();
         }
         }
         updateContentOffset(); // init
         setInterval(updateContentOffset, 100); // loop
         // overwrite ScrollMagic scrolltop getter
         scrollMagic.scrollPos(function(e) {
         return -contentOffset;
         });
         */
    });
    $(document).ready(function () {
        $('#fullpage').fullpage({
            scrollBar: true
        });
    });
</script>