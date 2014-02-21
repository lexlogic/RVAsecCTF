<?php
require_once '../Init.php';
$user = new User();
if($user->isLoggedIn()) {

    $page = new Page;
    $page->setTitle('Scoreboard');
    $page->startBody();
    ?>
    <style>
        #specialstuff {
            width: 100%;
            height: 100%;
            background-color: white;
        }
    </style>
    <div id="specialstuff">
        <br><br>
        <div align="center"><img src="../assets/images/logo-horizontal-inv.png" alt="" /></div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-9">
                <iframe src="reload.php" frameborder="0" allowtransparency="true" width="100%" style="min-height: 600px" seamless></iframe>
            </div>
            <div class="col-md-3">
                <a class="twitter-timeline" href="https://twitter.com/SecuraBit" data-widget-id="434770371246432257">
                    Tweets by @SecuraBit
                </a>
                <script>
                    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
                        if(!d.getElementById(id)){js=d.createElement(s);
                            js.id=id;js.src=p+"://platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
                </script>
            </div>
        </div>
        <span id="fsstatus"></span>
    </div>
    <?php
    $page->endBody();
    echo $page->render('../includes/template.php');
} else {
    Redirect::to('../login/');
}
?>
<script>

    /*
     Native FullScreen JavaScript API
     -------------
     Assumes Mozilla naming conventions instead of W3C for now
     */

    (function() {
        var
            fullScreenApi = {
                supportsFullScreen: false,
                isFullScreen: function() { return false; },
                requestFullScreen: function() {},
                cancelFullScreen: function() {},
                fullScreenEventName: '',
                prefix: ''
            },
            browserPrefixes = 'webkit moz o ms khtml'.split(' ');

        // check for native support
        if (typeof document.cancelFullScreen != 'undefined') {
            fullScreenApi.supportsFullScreen = true;
        } else {
            // check for fullscreen support by vendor prefix
            for (var i = 0, il = browserPrefixes.length; i < il; i++ ) {
                fullScreenApi.prefix = browserPrefixes[i];

                if (typeof document[fullScreenApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
                    fullScreenApi.supportsFullScreen = true;

                    break;
                }
            }
        }

        // update methods to do something useful
        if (fullScreenApi.supportsFullScreen) {
            fullScreenApi.fullScreenEventName = fullScreenApi.prefix + 'fullscreenchange';

            fullScreenApi.isFullScreen = function() {
                switch (this.prefix) {
                    case '':
                        return document.fullScreen;
                    case 'webkit':
                        return document.webkitIsFullScreen;
                    default:
                        return document[this.prefix + 'FullScreen'];
                }
            }
            fullScreenApi.requestFullScreen = function(el) {
                return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
            }
            fullScreenApi.cancelFullScreen = function(el) {
                return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
            }
        }

        // jQuery plugin
        if (typeof jQuery != 'undefined') {
            jQuery.fn.requestFullScreen = function() {

                return this.each(function() {
                    var el = jQuery(this);
                    if (fullScreenApi.supportsFullScreen) {
                        fullScreenApi.requestFullScreen(el);
                    }
                });
            };
        }

        // export api
        window.fullScreenApi = fullScreenApi;
    })();

</script>
<script>

    // do something interesting with fullscreen support
    var fsButton = document.getElementById('fsbutton'),
        fsElement = document.getElementById('specialstuff'),
        fsStatus = document.getElementById('fsstatus');


    if (window.fullScreenApi.supportsFullScreen) {
        fsStatus.innerHTML = '';
        fsStatus.className = 'fullScreenSupported';

        // handle button click
        fsButton.addEventListener('click', function() {
            window.fullScreenApi.requestFullScreen(fsElement);
        }, true);

        fsElement.addEventListener(fullScreenApi.fullScreenEventName, function() {
            if (fullScreenApi.isFullScreen()) {
                fsStatus.innerHTML = '';
            } else {
                fsStatus.innerHTML = '';
            }
        }, true);

    } else {
        fsStatus.innerHTML = 'SORRY: Your browser does not support FullScreen';
    }

</script>