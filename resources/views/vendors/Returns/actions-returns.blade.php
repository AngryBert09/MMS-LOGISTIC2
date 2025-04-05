<div class="dropdown">
    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button"
        data-toggle="dropdown">
        <i class="dw dw-more"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
        @if ($return['return_status'] == 'PENDING')
            <!-- Show Approve & Reject Actions if Pending -->
            <a href="javascript:void(0);" class="dropdown-item update-status" data-id="{{ $return['id'] }}"
                data-status="Approved">
                <i class="dw dw-check"></i> Confirm
            </a>

            <a href="javascript:void(0);" class="dropdown-item update-status" data-id="{{ $return['id'] }}"
                data-status="Rejected">
                <i class="dw dw-trash"></i> Reject
            </a>
        @else
            <span class="dropdown-item text-muted">No actions available</span>
        @endif
    </div>
</div>

<!-- JavaScript for AJAX Call -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".update-status").forEach(button => {
            button.addEventListener("click", function() {
                let returnId = this.getAttribute("data-id");
                let status = this.getAttribute("data-status");

                if (!confirm(`Are you sure you want to ${status.toLowerCase()} this return?`)) {
                    return;
                }

                fetch(`/returns/update/${returnId}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            location.reload(); // Refresh page after update
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Failed to update return status.");
                    });
            });
        });
    });
</script>
