<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Biddings</title>

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
    <!-- <div class="pre-loader">
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
  </div> -->


    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="container mx-auto p-4">
                    <div class="header-search">
                        <form id="searchForm">
                            <div class="form-group mb-0">
                                <i class="dw dw-search2 search-icon" onclick="searchRedirect()"></i> <!-- Search Icon -->
                                <input type="text" id="searchInput" class="form-control search-input" placeholder="Search Here"
                                    onkeyup="showSuggestions(this.value)" />
                            </div>

                            <!-- Dropdown suggestions -->
                            <div id="suggestionsDropdown" class="dropdown-menu" style="display:none;">
                                <ul id="suggestionsList" class="list-unstyled"></ul>
                            </div>
                        </form>
                        <script>
                            // Keywords that might be typed
                            const suggestions = [
                                'purchase orders', 'dashboard', 'invoices', 'receipts', 'chat', 'bidding',
                                'purchase receipt', 'purchase order'
                            ];

                            // Show suggestions based on input
                            function showSuggestions(query) {
                                const suggestionsDropdown = document.getElementById('suggestionsDropdown');
                                const suggestionsList = document.getElementById('suggestionsList');
                                suggestionsList.innerHTML = '';

                                if (query.trim()) {
                                    // Case-insensitive matching
                                    const filtered = suggestions.filter(s => s.toLowerCase().includes(query.toLowerCase()));
                                    if (filtered.length) {
                                        suggestionsDropdown.style.display = 'block';
                                        filtered.forEach(s => {
                                            const item = document.createElement('li');
                                            item.textContent = s;
                                            item.classList.add('dropdown-item');
                                            item.onclick = () => searchRedirect(s);
                                            suggestionsList.appendChild(item);
                                        });
                                    } else {
                                        suggestionsDropdown.style.display = 'block';
                                        const noResultItem = document.createElement('li');
                                        noResultItem.textContent = 'No Result Found';
                                        noResultItem.classList.add('dropdown-item', 'text-muted');
                                        suggestionsList.appendChild(noResultItem);
                                    }
                                } else {
                                    suggestionsDropdown.style.display = 'none';
                                }
                            }

                            // Redirect based on the selected suggestion
                            function searchRedirect(query = document.getElementById('searchInput').value) {
                                const pages = {
                                    'chat': '/chat',
                                    'purchase orders': '/purchase-orders',
                                    'dashboard': '/',
                                    'invoices': '/invoices',
                                    'receipts': '/receipts',
                                    'bidding': '/biddings',
                                    'purchase receipt': '/purchase-receipt',
                                    'purchase order': '/purchase-order'
                                };
                                const url = pages[query.toLowerCase()];
                                if (url) window.location.href = url;
                                document.getElementById('suggestionsDropdown').style.display = 'none';
                            }
                        </script>
                    </div>







            </div>

        </div>
    </div>


    <!-- Core JavaScript -->
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
