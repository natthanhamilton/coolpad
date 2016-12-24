<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.min.css"/>
<link rel="stylesheet" type="text/css" media="all"
      href="<?php echo get_template_directory_uri() . '/assets/css/rfp-octane.css'; ?>"/>
<div id="frame-container">
    <div id="frame" style="width: 225px; margin: auto">
        <div id="phone-frame"
             style='background-image: url("<?php echo get_template_directory_uri() . '/rfp/octane/empty.png'; ?>")'>
            <img id="default"
                 src="<?php echo get_template_directory_uri() . "/rfp/shared/nougat.png"; ?>">
            <img id="screen"
                 src="">
            <video autoplay="autoplay" loop id="video" src=""></video>
            <img class="animations" id="print1"
                 src="<?php echo get_template_directory_uri() . '/rfp/shared/map.png'; ?>">
            <img class="animations" id="print2"
                 src="<?php echo get_template_directory_uri() . '/rfp/shared/contacts.png'; ?>">
            <img class="animations" id="print3"
                 src="<?php echo get_template_directory_uri() . '/rfp/shared/facebook.png'; ?>">
        </div>
    </div>
</div>
<div id="fullpage">
    <div id="cover-trigger" style="position: absolute; top:300px;"></div>
    <div id="cover-bg" class="section">
        <div id="cover">
            <div style="background: rgba(0,0,0,0.3);">
                <div class="container vertical-center fullvh">
                    <div>
                        <div class="row" style="height: 20%">
                            <div class="col-xs-1" style="height: 115px">
                                <div class="pulse_holder">
                                    <div class="pulse_marker" style="float: right;">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <p>
                                    Mainly Motivated mid-incomers
                                </p>
                            </div>
                            <div class="col-sm-4 col-sm-offset-2">
                                <div class="pulse_holder">
                                    <div class="pulse_marker">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                                <p>
                                    Budget family or individual users.
                                </p>
                            </div>
                        </div>
                        <div class="row" style="height: 20%">
                            <div class="col-sm-12 text-center">
                                <h1 style="font-size: 8rem; color: #fff">Coolpad Octane</h1>
                            </div>
                        </div>
                        <div class="row" style="height: 20%">
                            <div class="col-sm-6">
                                <p>
                                    Blue collars and smartphone late adopters.
                                </p>
                                <div class="pulse_holder">
                                    <div class="pulse_marker" style="float: left;">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-sm-offset-1">
                                <div class="pulse_holder">
                                    <div class="pulse_marker" style="float: right;">
                                        <div class="pulse_rays small"></div>
                                    </div>
                                </div>
                                <p>
                                    Use phone mainly for communication and limited internet browsing.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh background" id="showcase"
         style="background: url('<?php echo get_template_directory_uri() . '/rfp/octane/design.png'; ?>')">
        <div class="container">
            <div class="row half-height bottom">
                <div class="col-sm-3 col-sm-offset-1 text-right">
                    <h1>Fuel up <br/> the look</h1>
                    <h3>Premium ID design</h3>
                </div>
            </div>
            <div class="row half-height">
                <div class="col-sm-4 col-sm-offset-8">
                    <h2>Don't compromise your fashion taste when things are affordable</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh background" id="playful"
         style="background: url('<?php echo get_template_directory_uri() . '/rfp/octane/playful.jpg'; ?>')">
        <div class="container">
            <div class="row half-height vertical">
                <div class="col-xs-12 text-center">
                    <div>
                        <h2>Your portable laptop for maximum <br/> video, reading, and gaming experience</h2>
                    </div>
                </div>
            </div>
            <div class="row half-height vertical">
                <div class="col-xs-12 text-center">
                    <div>
                        <h1>Fuel up the size</h1>
                        <h3>5.5" HD IPS Display</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh" id="fingerprint">
        <div class="container">
            <div class="row vertical">
                <div class="col-sm-3 col-sm-offset-1">
                    <h1>Fuel up the productivity</h1>
                    <h3>Multi-fingerprint</h3>
                    <ul id="fingerprint-list">
                        <li>Unlock</li>
                        <li>App shortcuts</li>
                        <li>Snapshot</li>
                    </ul>
                    <img class="img-responsive"
                         src="<?php echo get_template_directory_uri() . '/rfp/octane/finger.png'; ?>">
                </div>
                <div class="col-sm-4 col-sm-offset-3">
                    <div class="pull-right background" style="width: 225px; height: 450px;
                        background: url('<?php echo get_template_directory_uri() . '/rfp/octane/back.png'; ?>'); ">
                        <div class="pulse_holder" id="touchPrint">
                            <div class="pulse_finger">
                                <div class="pulse_rays"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh background" id="lifestyle"
         style="background: url('<?php echo get_template_directory_uri() . '/rfp/octane/camera.png'; ?>')">
        <div class="container">
            <div class="row" style="height: 45%">
                <div class="col-sm-4 col-sm-offset-8 full-height bottom">
                    <div>
                        <h2>Fuel up the pixel</h2>
                        <h3>13MP + 5MP</h3>
                        <p>Never miss a pixel in every special moment. 5.5" IPS Panel ensures a great viewing angle and
                            a high-color re-production</p>
                    </div>
                </div>
            </div>
            <div class="row bottom" style="height: 55%">
                <div class="col-sm-4 col-sm-offset-8">
                    <div>
                        <img class="img-responsive"
                             src="<?php echo get_template_directory_uri() . '/rfp/octane/angled.png'; ?>"
                             style="height: 450px">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section fullvh background" id="teaser"
         style="background: url('<?php echo get_template_directory_uri() . '/rfp/shared/background.jpg'; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div style="margin-top: 10%;">
                        <h1 style="color: #fff">Fuel up your life with <br/> Coolpad Octane</h1>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
<script type="text/javascript">
    $(function () {
        var scrollMagic = new ScrollMagic.Controller();

        /* Shims */
        var zeroRotateFrameTween = TweenMax.to('#phone-frame', 0, {rotation: 0});
        var zeroRotateFrame = new ScrollMagic.Scene({triggerElement: '#cover'}).setTween(zeroRotateFrameTween).addTo(scrollMagic);
        var zeroRotateScreenTween = TweenMax.to('#screen', 0, {rotation: 0, display: 'block'});
        var zeroRotateScreen = new ScrollMagic.Scene({triggerElement: '#cover'}).setTween(zeroRotateScreenTween).addTo(scrollMagic);
        var coverHideprint1Layer = TweenMax.to('#print1', 0, {display: 'none'});
        var coverHide2 = new ScrollMagic.Scene({triggerElement: '#cover'}).setTween(coverHideprint1Layer).addTo(scrollMagic);
        var coverHideprint2Layer = TweenMax.to('#print2', 0, {display: 'none'});
        var coverHide3 = new ScrollMagic.Scene({triggerElement: '#cover'}).setTween(coverHideprint2Layer).addTo(scrollMagic);
        var coverHideprint3Layer = TweenMax.to('#print3', 0, {display: 'none'});
        var coverHide4 = new ScrollMagic.Scene({triggerElement: '#cover'}).setTween(coverHideprint3Layer).addTo(scrollMagic);

        /* Cover */
        var tweenBackground = TweenMax.to('#cover', 0.5, {opacity: 0});
        var background = new ScrollMagic.Scene({
            triggerElement: '#cover-trigger',
            duration: 1000
        }).setTween(tweenBackground).addTo(scrollMagic);
        var screenTween = TweenMax.to('#screen', 0, {
            display: 'none'
        });
        var screen = new ScrollMagic.Scene({triggerElement: '#cover'}).setTween(screenTween).addTo(scrollMagic);


        /* design */
        var defaultTween = TweenMax.to('#default', 0, {
            display: 'none'
        });
        var defaultt = new ScrollMagic.Scene({triggerElement: '#showcase'}).setTween(defaultTween).addTo(scrollMagic);
        var centerPhone = TweenMax.to('#frame', 0.5, {top: '50%'});
        var phone = new ScrollMagic.Scene({triggerElement: '#showcase'}).setTween(centerPhone).addTo(scrollMagic);

        var showcaseHideprint1Layer = TweenMax.to('#print1', 0, {display: 'none'});
        var showcaseHide2 = new ScrollMagic.Scene({triggerElement: '#showcase'}).setTween(showcaseHideprint1Layer).addTo(scrollMagic);

        var showcaseHideprint2Layer = TweenMax.to('#print2', 0, {display: 'none'});
        var showcaseHide3 = new ScrollMagic.Scene({triggerElement: '#showcase'}).setTween(showcaseHideprint2Layer).addTo(scrollMagic);

        var showcaseHideprint3Layer = TweenMax.to('#print3', 0, {display: 'none'});
        var showcaseHide4 = new ScrollMagic.Scene({triggerElement: '#showcase'}).setTween(showcaseHideprint3Layer).addTo(scrollMagic);

        var showcaseTween = TweenMax.to('#screen', 0, {
            src: '<?php echo get_template_directory_uri() . "/rfp/octane/screenDesign.jpg"; ?>',
            display: 'block',
            height: 370,
            top: 38,
            left: 9
        });
        var showcase = new ScrollMagic.Scene({triggerElement: '#showcase'}).setTween(showcaseTween).addTo(scrollMagic);


        /* PLAYFUL */
        var playfulHideprint1Layer = TweenMax.to('#print1', 0, {display: 'none'});
        var playfulHide2 = new ScrollMagic.Scene({triggerElement: '#playful'}).setTween(playfulHideprint1Layer).addTo(scrollMagic);

        var playfulHideprint2Layer = TweenMax.to('#print2', 0, {display: 'none'});
        var playfulHide3 = new ScrollMagic.Scene({triggerElement: '#playful'}).setTween(playfulHideprint2Layer).addTo(scrollMagic);

        var playfulHideprint3Layer = TweenMax.to('#print3', 0, {display: 'none'});
        var playfulHide4 = new ScrollMagic.Scene({triggerElement: '#playful'}).setTween(playfulHideprint3Layer).addTo(scrollMagic);

        var playfulPhoneFrameTween = TweenMax.to('#phone-frame', 0, {
            rotation: 90,
            transition: '1s ease'
        });
        var playfulRotatePhone = new ScrollMagic.Scene({triggerElement: '#playful'}).setTween(playfulPhoneFrameTween).addTo(scrollMagic);

        var playfulTween = TweenMax.to('#screen', 0, {
            src: '<?php echo get_template_directory_uri() . "/rfp/octane/screen-playful.png"; ?>',
            opacity: 1,
            rotation: -90,
            left: -72,
            top: 118,
            height: 210
        });
        var playful = new ScrollMagic.Scene({triggerElement: '#playful'}).setTween(playfulTween).addTo(scrollMagic);


        /* FINGERPRINT */
        var fingerprintRotateScreenTween = TweenMax.to('#screen', 0, {rotation: 0, display: 'none'});
        var fingerprintRotateScreen = new ScrollMagic.Scene({triggerElement: '#fingerprint'}).setTween(fingerprintRotateScreenTween).addTo(scrollMagic);

        var fingerprintPhoneFrameTween = TweenMax.to('#phone-frame', 0, {rotation: 0});
        var fingerprintRotatePhone = new ScrollMagic.Scene({triggerElement: '#fingerprint'}).setTween(fingerprintPhoneFrameTween).addTo(scrollMagic);

        var fingerPrintAnimation = new TimelineMax({delay: 0, repeat: 20});
        fingerPrintAnimation.from("#print1", 2, {display: 'block'})
            .to("#print1", 0, {display: 'none'})
            .to("#print2", 2, {display: 'block'})
            .to("#print2", 0, {display: 'none'})
            .to("#print3", 2, {display: 'block'})
            .to("#print3", 0, {display: 'none'});
        var fingerprint = new ScrollMagic.Scene({triggerElement: '#fingerprint'}).setTween(fingerPrintAnimation).addTo(scrollMagic);


        /* LIFESTYLE */
        var LifestyleHideprint1Layer = TweenMax.to('#print1', 0, {display: 'none'});
        var lifestyleHide2 = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(LifestyleHideprint1Layer).addTo(scrollMagic);

        var LifestyleHideprint2Layer = TweenMax.to('#print2', 0, {display: 'none'});
        var lifestyleHide3 = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(LifestyleHideprint2Layer).addTo(scrollMagic);

        var LifestyleHideprint3Layer = TweenMax.to('#print3', 0, {display: 'none'});
        var lifestyleHide4 = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(LifestyleHideprint3Layer).addTo(scrollMagic);

        var lifestylePhoneFrameTween = TweenMax.to('#phone-frame', 0, {transition: 'none'});
        var lifestyleRotatePhone = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(lifestylePhoneFrameTween).addTo(scrollMagic);

        // lifestyle - Show Image
        var lifestyleTween = TweenMax.to('#screen', 0, {
            src: '<?php echo get_template_directory_uri() . "/rfp/octane/screen-camera.jpg"; ?>',
            display: 'block',
            height: 370,
            top: 38,
            left: 9,
            transition: 0
        });
        var lifestyle = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(lifestyleTween).addTo(scrollMagic);

        /* TEASER */
        var LifestyleHideprint1Layer = TweenMax.to('#print1', 0, {top: -10000});
        var lifestyleHide2 = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(LifestyleHideprint1Layer).addTo(scrollMagic);

        var LifestyleHideprint2Layer = TweenMax.to('#print2', 0, {top: -10000});
        var lifestyleHide3 = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(LifestyleHideprint2Layer).addTo(scrollMagic);

        var LifestyleHideprint3Layer = TweenMax.to('#print3', 0, {top: -10000});
        var lifestyleHide4 = new ScrollMagic.Scene({triggerElement: '#lifestyle'}).setTween(LifestyleHideprint3Layer).addTo(scrollMagic);

        var teaserRotateScreenTween = TweenMax.to('#screen', 0, {rotation: 0, display: 'none'});
        var teaserRotateScreen = new ScrollMagic.Scene({triggerElement: '#teaser'}).setTween(teaserRotateScreenTween).addTo(scrollMagic);

        var teaserTween = TweenMax.to('#video', 0, {
            src: '<?php echo get_template_directory_uri() . "/rfp/octane/teaser.mp4"; ?>',
            opacity: 1,
            rotation: 270,
            height: 292,
            top: 176,
            left: -94
        });
        var teaser = new ScrollMagic.Scene({
            triggerElement: '#teaser'
        }).setTween(teaserTween).addTo(scrollMagic);

        // Playful - Rotate frame and change width
        var teaserRotateTween = TweenMax.to('#frame', 0, {
            width: 325
        });
        var teaserRotate = new ScrollMagic.Scene({
            triggerElement: '#teaser'
        }).setTween(teaserRotateTween).addTo(scrollMagic);

        // Playful - increase phone-frame size
        var teaserPhoneFrameTween = TweenMax.to('#phone-frame', 0, {
            rotation: 90,
            height: 650,
            transition: '1s ease'
        });
        var teaserPhoneFrame = new ScrollMagic.Scene({
            triggerElement: '#teaser'
        }).setTween(teaserPhoneFrameTween).addTo(scrollMagic);
    });
    $(document).ready(function () {
        $('#fullpage').fullpage({
            scrollBar: true
        });
    });
</script>