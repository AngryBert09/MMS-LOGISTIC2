<!DOCTYPE html>
<html>

@include('layout.head')

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
                @include('layout.breadcrumb')
                <div x-data="chatApp()" x-init="init();" class="bg-white border-radius-4 box-shadow mb-30">
                    <div class="row no-gutters">
                        <!-- Chat List Column -->
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="chat-list bg-light-gray">
                                <!-- Search Bar -->
                                <div class="chat-search">
                                    <span class="ti-search"></span>
                                    <input type="text" placeholder="Search Contact" x-model="searchQuery" />
                                </div>

                                <!-- Vendor List -->
                                <div class="notification-list chat-notification-list customscroll">
                                    <ul>
                                        <template x-for="vendor in filteredVendors" :key="vendor.id">
                                            <li @click="selectVendor(vendor)"
                                                :class="{ 'active': currentVendor?.id === vendor.id }"
                                                class="vendor-link" x-show="vendor.id !== authVendorId">
                                                <a href="#">
                                                    <img :src="vendor.profile_pic || '{{ asset('images/default.jpg') }}'"
                                                        alt="Vendor Profile" />
                                                    <h3 x-text="vendor.company_name"></h3>
                                                    <p>
                                                        <i
                                                            :class="vendor.is_online ? 'fa fa-circle text-light-green' :
                                                                'fa fa-circle text-warning'"></i>
                                                        <span x-text="vendor.is_online ? 'Online' : 'Offline'"></span>
                                                        <span x-text="vendor.unread_messages_count || 0"
                                                            class="unread-count"></span>
                                                    </p>

                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <!-- Chat Detail Column -->
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <div class="chat-detail">
                                <!-- Chat Header -->
                                <div class="chat-profile-header clearfix" x-show="currentVendor">
                                    <div class="left">
                                        <div class="clearfix">
                                            <div class="chat-profile-photo">
                                                <img :src="currentVendor?.profile_pic || '{{ asset('images/default.jpg') }}'"
                                                    alt="Vendor Profile" />
                                            </div>
                                            <div class="chat-profile-name">
                                                <h3 class="text-warning"
                                                    x-text="currentVendor ? currentVendor.company_name : 'No Vendor Selected'">
                                                </h3>
                                                <span
                                                    x-text="currentVendor ? currentVendor.address : 'No address available'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-outline-warning dropdown-toggle" href="#"
                                                role="button" data-toggle="dropdown">
                                                Settings
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

                                <!-- Chat Box -->
                                <div class="chat-box">
                                    <div x-show="!currentVendor" class="no-message-selected">
                                        <p>No Message Selected</p>
                                    </div>
                                    <div class="chat-desc" x-ref="chatContainer">
                                        <ul>
                                            <template x-for="message in messages"
                                                :key="message.message_id || message.created_at || Math.random()">
                                                <li
                                                    :class="{
                                                        'clearfix': true,
                                                        'admin_chat': message.sender_id ===
                                                            authVendorId
                                                    }">
                                                    <span class="chat-img">
                                                        <img :src="message.sender_id === authVendorId ?
                                                            '{{ Auth::user()->profile_pic }}' :
                                                            (currentVendor?.profile_pic ? currentVendor.profile_pic :
                                                                '{{ asset('images/default.jpg') }}')"
                                                            alt="Chat Image" />
                                                    </span>
                                                    <div class="chat-body clearfix">
                                                        <p
                                                            x-text="typeof message.message === 'string' ? message.message : message.message.message">
                                                        </p>
                                                        <div class="chat_time">
                                                            <span
                                                                x-text="formatDate(message.created_at) || 'Invalid Date'"></span>
                                                            <span x-text="formatTime(message.created_at)"></span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="chat-footer" x-show="currentVendor">
                                        <div class="file-upload">
                                            <a href="#"><i class="fa fa-paperclip"></i></a>
                                        </div>
                                        <div class="chat_text_area">
                                            <textarea placeholder="Type your message..." x-model="newMessage" @keydown.enter.prevent="sendMessage()">
                                        </textarea>
                                        </div>
                                        <div class="chat_send">
                                            <button @click="sendMessage()" class="btn btn-link" type="button">
                                                <i class="icon-copy ion-paper-airplane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meta Data for User -->
                <div id="user-data" data-user-id="{{ auth()->id() }}"
                    data-selected-vendor-id="{{ $selectedVendorId ?? 'null' }}"></div>
            </div>

        </div>
    </div>


    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function chatApp() {
            return {
                vendors: @json($vendors),
                currentVendor: null, // No vendor selected initially
                messages: [],
                newMessage: '',
                authVendorId: {{ auth()->id() }},
                searchQuery: '',
                pusher: null,
                loadingMessages: false,

                get filteredVendors() {
                    return this.vendors.filter((vendor) =>
                        vendor.company_name.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                init() {
                    this.pusher = new Pusher('e91ebdcbd5414615fcb8', {
                        cluster: 'eu',
                        encrypted: true,
                    });
                    console.log('Pusher initialized.');

                    this.currentVendor = null;
                    this.messages = [];
                    this.listenForOnlineStatusChanges();
                    this.fetchVendors();
                },

                selectVendor(vendor) {
                    this.currentVendor = vendor;
                    this.messages = [];
                    this.loadMessages(vendor.id);
                    this.markAsRead(vendor.id);

                    if (this.channel) {
                        this.pusher.unsubscribe(this.channel.name); // Unsubscribe from the old channel
                    }

                    this.listenToVendorChannel(); // Resubscribe to the general channel
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });

                },

                fetchVendors() {
                    console.log('Fetching vendors...'); // Log when the fetch starts

                    fetch('{{ route('vendors.unread') }}')
                        .then(response => {

                            return response.json();
                        })
                        .then(data => {
                            console.log('Vendors data:', data); // Log the data you received
                            this.vendors = data;
                        })
                        .catch(error => {
                            console.error('Error fetching vendors:', error); // Log any error that occurs
                        });
                },


                markAsRead(vendorId) {
                    fetch(`/mark-as-read/${vendorId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                throw new Error('Unexpected response type, expected JSON');
                            }
                        })
                        .then(data => {
                            if (data.success) {
                                console.log('Messages marked as read successfully:', data);
                                this.vendors = this.vendors.map(vendor => {
                                    if (vendor.id === vendorId) {
                                        vendor.unread_messages_count = 0;
                                    }
                                    return vendor;
                                });
                            } else {
                                console.error('Failed to mark messages as read:', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error marking messages as read:', error);
                        });
                },

                listenForOnlineStatusChanges() {
                    const channel = this.pusher.subscribe('vendor-status');
                    channel.bind('status-updated', (data) => {
                        console.log('Received vendor status update:', data);

                        // Find the vendor and update its status
                        const updatedVendor = this.vendors.find(vendor => vendor.id === data.vendor_id);
                        if (updatedVendor) {
                            updatedVendor.is_online = data.is_online;

                            // Trigger Alpine.js reactivity
                            this.vendors = [...this.vendors]; // Create a shallow copy to trigger re-render
                        }
                    });
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    const options = {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    };
                    return date.toLocaleDateString('en-US', options);
                },

                formatTime() {
                    const now = new Date();
                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                },

                sendMessage() {
                    if (this.newMessage.trim() === '') return;

                    fetch('/send-message', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                            body: JSON.stringify({
                                message: this.newMessage,
                                receiver_id: this.currentVendor.id, // Dynamically set receiver
                                sender_id: this.authVendorId,
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message_id) {
                                this.messages.push(data); // Directly push the response data
                                this.newMessage = ''; // Clear input field
                                this.scrollToBottom();
                            } else {
                                console.error("Error in response format:", data);
                            }
                        })
                        .catch(error => {
                            console.error("Error sending message:", error);
                        });
                },


                loadMessages(vendorId) {
                    this.loadingMessages = true;

                    // Fetch messages for the selected vendor using fetch API
                    fetch(`messages/${vendorId}?auth_vendor_id=${this.authVendorId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.messages) {
                                this.messages = data.messages; // Set messages from the backend
                            }
                            this.loadingMessages = false;

                            // After messages are loaded, scroll to the bottom
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        })
                        .catch(error => {
                            console.error("Error loading messages:", error);
                            this.loadingMessages = false;
                        });
                },


                listenToVendorChannel() {
                    console.log('Listening for real-time messages');

                    // Create a Set to track message IDs for duplicate checking
                    if (!this.messageIds) {
                        this.messageIds = new Set();
                    }

                    // Subscribe to the chat channel
                    const channel = this.pusher.subscribe('chat');

                    // Bind to the 'message-sent' event
                    channel.bind('message-sent', (message) => {
                        // Process the message only if it is for the authenticated user
                        if (message.message.receiver_id === this.authVendorId) {
                            // Check if the message already exists using the Set
                            if (!this.messageIds.has(message.message.message_id)) {
                                console.log('Incoming message for this user:', message);

                                // Add the message to the array and the Set
                                this.messages.push(message.message);
                                this.messageIds.add(message.message.message_id);

                                // Scroll to the bottom of the chat
                                this.scrollToBottom();
                            } else {
                                console.log('Duplicate message detected, ignoring.');
                            }
                        }
                    });
                },


                scrollToBottom() {
                    this.$nextTick(() => {
                        if (this.$refs.chatContainer) {
                            const container = this.$refs.chatContainer;
                            const scrollHeight = container.scrollHeight;
                            const clientHeight = container.clientHeight;


                            if (scrollHeight > clientHeight) {

                                container.scrollTop = scrollHeight - clientHeight;
                            }
                        }
                    });



                }

            };
        }
    </script>






    <!-- js -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/cropperjs/dist/cropper.js') }}"></script>



    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
