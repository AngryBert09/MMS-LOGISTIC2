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

    @include('admin.layout.navbar')

    @include('admin.layout.left-sidebar')
    @include('admin.layout.right-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="container pd-0">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title d-flex align-items-center">
                                    <h4 class="mb-0">Supplier Performance Analysis</h4>
                                    <span class="badge badge-light text-dark ml-2">🤖 powered by AI</span>
                                </div>
                                <p id="dynamicQuestion" class="text-dark mt-2">
                                    🧐 Are suppliers meeting our on-time delivery benchmarks?
                                </p>

                                <!-- Buttons -->
                                <div class="d-flex align-items-center mt-2">
                                    <button id="toggleCustomPrompt" class="btn btn-sm btn-secondary mr-2">
                                        ✏️ Ask Custom Question
                                    </button>
                                    <button id="analyzeQuestionBtn" class="btn btn-sm btn-primary mr-2">
                                        📊 Analyze with AI
                                    </button>
                                    <button id="stopQuestionBtn" class="btn btn-sm btn-danger">
                                        🛑 Stop Questions
                                    </button>
                                </div>

                                <!-- Custom Prompt Input (Initially Hidden) -->
                                <div id="customPromptContainer" class="form-group mt-2" style="display: none;">
                                    <input type="text" id="customQuestionInput" class="form-control form-control-sm"
                                        placeholder="Type your custom question here...">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" id="useCustomQuestion">
                                        <label class="form-check-label" for="useCustomQuestion">Use custom
                                            question</label>
                                    </div>
                                </div>

                                <!-- AI Analysis Result -->
                                <div id="analysisResult" class="mt-3 text-dark font-weight-bold p-2 border rounded"
                                    style="max-height: 250px; overflow-y: auto; background: #f8f9fa; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const questions = [
                                "📦 How consistently do suppliers meet on-time delivery expectations?",
                                "⚠️ How frequently do suppliers deliver defective or returned products?",
                                "💰 How much do supplier costs fluctuate compared to initial agreements?",
                                "⏳ How quickly do suppliers resolve issues and respond to inquiries?",
                                "✅ How well do suppliers comply with contract terms and quality standards?"
                            ];

                            let index = 0;
                            let interval = setInterval(changeQuestion, 5000);
                            const questionElement = document.getElementById("dynamicQuestion");
                            const customPromptContainer = document.getElementById("customPromptContainer");
                            const customQuestionInput = document.getElementById("customQuestionInput");
                            const useCustomQuestionCheckbox = document.getElementById("useCustomQuestion");
                            const analyzeBtn = document.getElementById("analyzeQuestionBtn");
                            const toggleCustomPromptBtn = document.getElementById("toggleCustomPrompt");
                            const analysisResult = document.getElementById("analysisResult");

                            function changeQuestion() {
                                if (!useCustomQuestionCheckbox.checked) { // Only change question if custom is NOT selected
                                    questionElement.style.opacity = 0;
                                    setTimeout(() => {
                                        questionElement.innerText = questions[index];
                                        questionElement.style.opacity = 1;
                                        index = (index + 1) % questions.length;
                                    }, 500);
                                }
                            }

                            // Toggle Custom Prompt Input
                            toggleCustomPromptBtn.addEventListener("click", function() {
                                if (customPromptContainer.style.display === "none") {
                                    customPromptContainer.style.display = "block";
                                    customQuestionInput.focus();
                                } else {
                                    customPromptContainer.style.display = "none";
                                    useCustomQuestionCheckbox.checked = false;
                                    interval = setInterval(changeQuestion, 5000); // Restart question rotation
                                }
                            });

                            // Stop predefined question rotation when custom is selected
                            useCustomQuestionCheckbox.addEventListener("change", function() {
                                if (this.checked) {
                                    clearInterval(interval); // Stop rotating questions
                                } else {
                                    interval = setInterval(changeQuestion, 5000); // Resume rotation
                                }
                            });

                            analyzeBtn.addEventListener("click", function() {
                                clearInterval(interval); // Stop question rotation

                                let selectedQuestion = useCustomQuestionCheckbox.checked ?
                                    customQuestionInput.value.trim() :
                                    questionElement.innerText;

                                if (!selectedQuestion) {
                                    alert("Please enter a question for AI analysis.");
                                    return;
                                }

                                // Show loading effect with animation
                                analysisResult.style.display = "block";
                                analysisResult.innerHTML = `
                                    <div class="text-center">
                                        <span class="spinner-border text-dark" role="status"></span>
                                        <p class="mt-2">Analyzing...</p>
                                    </div>`;

                                // Simulating AI API Call
                                setTimeout(() => {
                                    fetch("{{ route('admin.analyzeSupplierPerformance') }}", {
                                            method: "POST",
                                            headers: {
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                "Content-Type": "application/json"
                                            },
                                            body: JSON.stringify({
                                                question: selectedQuestion
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            analysisResult.innerHTML =
                                                `<p class="text-dark fade-in" style="font-weight: normal;">${data.report}</p>`;

                                        })
                                        .catch(error => {
                                            console.error("Error:", error);
                                            analysisResult.innerHTML =
                                                `<p class="text-danger fade-in">❌ Analysis failed. Please try again.</p>`;
                                        });
                                }, 2000);
                            });

                            document.getElementById("stopQuestionBtn").addEventListener("click", function() {
                                clearInterval(interval);
                            });
                        });
                    </script>

                    <style>
                        .fade-in {
                            opacity: 0;
                            animation: fadeInAnimation 1s ease-in forwards;
                        }

                        @keyframes fadeInAnimation {
                            from {
                                opacity: 0;
                            }

                            to {
                                opacity: 1;
                            }
                        }
                    </style>



                    <div class="contact-directory-list">
                        <ul class="row">
                            @foreach ($vendors as $vendor)
                                <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="contact-directory-box">
                                        <div class="contact-dire-info text-center">
                                            <div class="contact-avatar">
                                                <span>
                                                    <img src="{{ asset($vendor->profile_pic ? $vendor->profile_pic : 'images/default.jpg') }}"
                                                        alt="" />
                                                </span>
                                            </div>
                                            <div class="contact-name">
                                                <h4>{{ $vendor->company_name }}</h4>
                                                <p>{{ $vendor->email }}</p>
                                                <div class="work text-success">
                                                    <i class="ion-android-person"></i> {{ $vendor->status }}
                                                </div>
                                            </div>
                                            <div class="contact-skill">
                                                @if ($vendor->on_time_delivery_rate >= 90)
                                                    <span class="badge badge-pill badge-success">📦 Excellent
                                                        Delivery</span>
                                                @elseif ($vendor->on_time_delivery_rate >= 75)
                                                    <span class="badge badge-pill badge-warning">🚚 Good Delivery</span>
                                                @else
                                                    <span class="badge badge-pill badge-danger">⏳ Needs
                                                        Improvement</span>
                                                @endif

                                                @if ($vendor->defect_rate < 5)
                                                    <span class="badge badge-pill badge-primary">🔍 High Quality</span>
                                                @elseif ($vendor->defect_rate < 10)
                                                    <span class="badge badge-pill badge-info">⚠️ Acceptable
                                                        Quality</span>
                                                @else
                                                    <span class="badge badge-pill badge-dark">❌ Quality Issues</span>
                                                @endif

                                                @if ($vendor->compliance_score >= 90)
                                                    <span class="badge badge-pill badge-success">✅ Fully
                                                        Compliant</span>
                                                @elseif ($vendor->compliance_score >= 75)
                                                    <span class="badge badge-pill badge-warning">⚠️ Partially
                                                        Compliant</span>
                                                @else
                                                    <span class="badge badge-pill badge-danger">🚨 Non-Compliant</span>
                                                @endif


                                            </div>
                                            {{-- <div class="profile-sort-desc">
                                                {{ $vendor->description }}
                                            </div> --}}
                                        </div>
                                        <div class="view-contact">
                                            <a href="{{ route('admin.vendor-profile', $vendor->id) }}">VENDOR
                                                ACCOUNT</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- welcome modal start -->

    <!-- welcome modal end -->
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
