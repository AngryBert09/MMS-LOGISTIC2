<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
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
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>
        <div class="user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                    <i class="icon-copy dw dw-notification"></i>
                    @if (auth()->user()->unreadNotifications->count())
                        <span
                            class="badge notification-active">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="notification-list mx-h-350 customscroll">
                        <ul>
                            @foreach (auth()->user()->unreadNotifications as $notification)
                                <li>
                                    <a href="#">
                                        <img src="images/img.jpg" alt="" />
                                        <h3>Admin</h3>
                                        <p>
                                            {{ $notification->data['message'] }}
                                        </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ asset(Auth::user()->profile_pic ? Auth::user()->profile_pic : 'images/default.jpg') }}"
                            alt="Profile Picture" />
                    </span>
                    <span class="user-name">{{ Auth::user()->full_name }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="{{ route('profiles.show', Auth::user()->id) }}">
                            <i class="dw dw-user1"></i> Profile
                        </a>
                        <a class="dropdown-item" href="profile.html"><i class="dw dw-settings2"></i> Setting</a>
                        <a class="dropdown-item" href="{{ route('faqs') }}"><i class="dw dw-help"></i> Help</a>
                        <button class="dropdown-item" type="submit"><i class="dw dw-logout"></i> Log Out</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
