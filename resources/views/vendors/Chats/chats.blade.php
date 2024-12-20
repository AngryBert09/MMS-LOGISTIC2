<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Chats</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/gwa-touch-icon') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gwa-favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-GBZ3SGGX85");
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
    </script>
    <!-- End Google Tag Manager -->
</head>

<body>
    {{-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="vendors/images/deskapp-logo.svg" alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div> --}}

    @include('layout.nav')
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')




    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Chat</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ asset('index.html') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Chat
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="bg-white border-radius-4 box-shadow mb-30">
                    <div class="row no-gutters">
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="chat-list bg-light-gray">
                                <div class="chat-search">
                                    <span class="ti-search"></span>
                                    <input type="text" placeholder="Search Contact" />
                                </div>
                                <div class="notification-list chat-notification-list customscroll">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <img src="{{ asset('images/img.jpg') }}" alt="" />
                                                <h3 class="clearfix">GWA</h3>
                                                <p>
                                                    <i class="fa fa-circle text-light-green"></i> online
                                                </p>
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="#">
                                                <img src="{{ asset('images/img.jpg') }}" alt="" />
                                                <h3 class="clearfix">GreatWallArts</h3>
                                                <p>
                                                    <i class="fa fa-circle text-light-green"></i> online
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <div class="chat-detail">
                                <div class="chat-profile-header clearfix">
                                    <div class="left">
                                        <div class="clearfix">
                                            <div class="chat-profile-photo">
                                                <img src="{{ asset('images/profile-photo.jpg') }}" alt="" />
                                            </div>
                                            <div class="chat-profile-name">
                                                <h3>GWA EMPLOYEE</h3>
                                                <span>New York, USA</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-outline-primary dropdown-toggle" href="#"
                                                role="button" data-toggle="dropdown">
                                                Setting
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Export Chat</a>
                                                <a class="dropdown-item" href="#">Search</a>
                                                <a class="dropdown-item text-light-orange" href="#">Delete
                                                    Chat</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-box">
                                    <div class="chat-desc customscroll">
                                        <ul>
                                            <li class="clearfix admin_chat">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img2.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <p>Maybe you already have additional info?</p>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                            <li class="clearfix admin_chat">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img2.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <p>
                                                        It is too early to provide some kind of estimation
                                                        here. We need user stories.
                                                    </p>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img1.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <p>
                                                        We are just writing up the user stories now so
                                                        will have requirements for you next week.
                                                    </p>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img1.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <p>
                                                        Essentially, the brief is for you guys to build an
                                                        iOS and Android app. We will do backend and web
                                                        app. If you have any early questions, please do send them on.
                                                    </p>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                            <li class="clearfix admin_chat">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img2.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <p>Maybe you already have additional info?</p>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                            <li class="clearfix upload-file">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img1.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <div class="upload-file-box clearfix">
                                                        <div class="left">
                                                            <img src="{{ asset('images/upload-file-img.jpg') }}"
                                                                alt="" />
                                                            <div class="overlay">
                                                                <a href="#">
                                                                    <span><i class="fa fa-angle-down"></i></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <h3>Big room.jpg</h3>
                                                            <a href="#">Download</a>
                                                        </div>
                                                    </div>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                            <li class="clearfix upload-file admin_chat">
                                                <span class="chat-img">
                                                    <img src="{{ asset('images/chat-img2.jpg') }}" alt="" />
                                                </span>
                                                <div class="chat-body clearfix">
                                                    <div class="upload-file-box clearfix">
                                                        <div class="left">
                                                            <img src="{{ asset('images/upload-file-img.jpg') }}"
                                                                alt="" />
                                                            <div class="overlay">
                                                                <a href="#">
                                                                    <span><i class="fa fa-angle-down"></i></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <h3>Big room.jpg</h3>
                                                            <a href="#">Download</a>
                                                        </div>
                                                    </div>
                                                    <div class="chat_time">09:40PM</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="chat-footer">
                                        <div class="file-upload">
                                            <a href="#"><i class="fa fa-paperclip"></i></a>
                                        </div>
                                        <div class="chat_text_area">
                                            <textarea placeholder="Type your message…"></textarea>
                                        </div>
                                        <div class="chat_send">
                                            <button class="btn btn-link" type="submit">
                                                <i class="icon-copy ion-paper-airplane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
