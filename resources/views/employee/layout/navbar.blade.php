<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
        <div class="header-search">
            <form>
                <div class="form-group mb-0">
                    <i class="dw dw-search2 search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Search Here" />
                    <div class="dropdown">
                        <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                            <i class="ion-arrow-down-c"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">From</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">To</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">Subject</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                    <span class="badge notification-active" id="notificationBadge" style="display: none;"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="notification-list mx-h-350 customscroll">
                        <ul id="notificationList">
                            <li id="noNotificationMessage" class="text-center text-muted p-3" style="display: none;">
                                <img src="{{ asset('images/greatwall-logo.png') }}" alt="Notification Image" />

                                <i class="dw dw-bell-off" style="font-size: 40px;"></i>
                                <p class="mt-2">No new notifications</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                function loadNotifications() {
                    $.ajax({
                        url: '/employee/notifications', // Adjust with your actual route
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                let notifications = response.notifications;
                                let notificationList = $('#notificationList');
                                let badge = $('#notificationBadge');
                                let noNotificationMessage = $('#noNotificationMessage');

                                notificationList.empty(); // Clear previous notifications

                                if (notifications.length > 0) {
                                    badge.text(notifications.length).show(); // Show badge count
                                    noNotificationMessage.hide(); // Hide "No notifications" message

                                    let notificationHTML = '';
                                    notifications.forEach(notification => {
                                        let url = notification.url ?
                                            `<a href="${notification.url}" class="btn btn-sm btn-primary mt-2">View Details</a>` :
                                            '';
                                        notificationHTML += `
                                            <li class="p-2 border-bottom">
                                                <a href="${notification.url ?? '#'}">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/greatwall-logo.png') }}" alt="Notification" class="me-2" style="width: 40px; height: 40px;">
                                                        <div>
                                                            <h6 class="mb-1">System</h6>
                                                            <p class="small text-muted mb-1">${notification.message}</p>
                                                            <small class="text-muted">${notification.created_at}</small>
                                                            ${url}
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        `;
                                    });

                                    notificationList.append(notificationHTML);
                                } else {
                                    badge.hide(); // Hide badge if no notifications
                                    noNotificationMessage.show(); // Show "No notifications" message
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching notifications:', error);
                        }
                    });
                }

                // Load notifications initially
                loadNotifications();

                // Refresh notifications every 10 seconds
                setInterval(loadNotifications, 10000);
            });
        </script>




        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ asset('images/default.jpg') }}" alt="" />
                    </span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="profile.html"><i class="dw dw-settings2"></i> Settings</a>
                    <form id="logout-form" action="{{ route('employee.logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="dw dw-logout"></i> Log Out
                    </a>
                </div>
            </div>
        </div>
        <div class="github-link">
            <a href="https://github.com/dropways/deskapp" target="_blank"><img src="vendors/images/github.svg"
                    alt="" /></a>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        function loadNotifications() {
            $.ajax({
                url: '/employee/notifications', // API route to get notifications
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let notifications = response.notifications;
                        let notificationList = $('#notificationList');
                        let badge = $('#notificationBadge');
                        let noNotificationMessage = $('#noNotificationMessage');

                        notificationList.empty(); // Clear previous notifications

                        if (notifications.length > 0) {
                            badge.text(notifications.length).show(); // Show badge count
                            noNotificationMessage.hide(); // Hide "No notifications" message
                        } else {
                            badge.hide(); // Hide badge if no notifications
                            noNotificationMessage.show(); // Show "No notifications" message
                        }

                        notifications.forEach(notification => {
                            let icon = getNotificationIcon(notification.type);
                            let url = notification.url ?
                                `<a href="${notification.url}">View Details</a>` : '';

                            notificationList.append(`
                                <li>
                                    <a href="#">
                                        <div class="d-flex align-items-center">
                                            <i class="${icon} text-warning me-2"></i>
                                            <div>
                                                <h6>${notification.title}</h6>
                                                <p>${notification.message}</p>
                                                <small class="text-muted">${notification.created_at}</small>
                                                ${url}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `);
                        });
                    }
                },
                error: function() {
                    console.log('Error fetching notifications');
                }
            });
        }

        function getNotificationIcon(type) {
            switch (type) {
                case 'procurement':
                    return 'dw dw-shopping-cart';
                case 'bidding':
                    return 'dw dw-money';
                case 'approval':
                    return 'dw dw-check';
                case 'general':
                    return 'dw dw-bell';
                default:
                    return 'dw dw-notification';
            }
        }

        // Load notifications initially
        loadNotifications();

        // Refresh notifications every 10 seconds
        setInterval(loadNotifications, 10000);
    });
</script>
