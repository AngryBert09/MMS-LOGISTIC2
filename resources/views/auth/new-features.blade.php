<!-- New Features Modal -->
<div class="modal fade" id="newFeaturesModal" tabindex="-1" aria-labelledby="newFeaturesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 800px;">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="newFeaturesModalLabel">🎉 New Features & Updates (v7.7.x) 🎉</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <p>We're thrilled to share some exciting updates with you! Here's what's new in <strong>v7.7.x</strong>:
                </p>
                <ul>
                    <li>
                        <strong>🔒 Enhanced Security with 2-Factor Authentication (2FA):</strong>
                        We've introduced two-factor authentication (2FA) to provide an extra layer of security for your
                        account.
                        Now, you can enjoy peace of mind knowing your data is protected with state-of-the-art security
                        measures.
                    </li>
                    <li>
                        <strong>⚡ Performance Optimization:</strong>
                        We've optimized the system for faster load times and smoother performance.
                        Enjoy a more responsive experience across all features and modules.
                    </li>
                    <li>
                        <strong>✨ Improved Dashboard:</strong>
                        Your dashboard just got a makeover! We've redesigned it to be more intuitive and user-friendly,
                        so you can focus on what matters most.
                    </li>
                    <li>
                        <strong>📊 New Reporting Tools:</strong>
                        Dive deeper into your data with our advanced reporting and analytics features.
                        Get insights like never before and make smarter decisions.
                    </li>
                </ul>
                <br>
                <p>We're constantly working to make your experience better. Stay tuned for more updates! 🚀</p>
            </div>
        </div>
    </div>
</div>
<script>
    // Automatically show the modal when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('newFeaturesModal'), {
            keyboard: true // Prevent closing the modal by pressing the ESC key
        });
        myModal.show();
    });
</script>
