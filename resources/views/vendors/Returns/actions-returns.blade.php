<div class="dropdown">
    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button"
        data-toggle="dropdown">
        <i class="dw dw-more"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
        <!-- View Action (Always Available) -->
        <a class="dropdown-item" href="{{ route('returns.show', $return->return_id) }}">
            <i class="dw dw-eye"></i> View
        </a>

        @if ($return->return_status !== 'Rejected')
            @if ($return->return_status === 'Approved')
                <!-- Show Process Action if Status is Approved -->
                <a href="javascript:void(0);" class="dropdown-item update-status" data-id="{{ $return->return_id }}"
                    data-status="Processed">
                    <i class="dw dw-refresh"></i> Process
                </a>
            @else
                <!-- Show Approve and Reject Actions if Not Approved -->
                <a href="javascript:void(0);" class="dropdown-item update-status" data-id="{{ $return->return_id }}"
                    data-status="Approved">
                    <i class="dw dw-check"></i> Confirm
                </a>

                <a href="javascript:void(0);" class="dropdown-item update-status" data-id="{{ $return->return_id }}"
                    data-status="Rejected">
                    <i class="dw dw-trash"></i> Reject
                </a>
            @endif
        @endif
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.update-status').forEach(item => {
            item.addEventListener('click', function() {
                let returnId = this.getAttribute('data-id');
                let newStatus = this.getAttribute('data-status');
                let statusElement = document.getElementById(`status-${returnId}`);

                fetch(`/returns/${returnId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            return_status: newStatus
                        })
                    })
                    .then(response => {
                        if (response.status === 204) {
                            return {
                                message: 'Return status updated successfully.'
                            };
                        }
                        return response.json();
                    })
                    .then(data => {


                        // Update the status text dynamically
                        statusElement.textContent = newStatus.charAt(0).toUpperCase() +
                            newStatus.slice(1);

                        // Update the badge color dynamically
                        statusElement.className = "badge";
                        if (newStatus === "Approved") {
                            statusElement.classList.add("badge-success");
                        } else if (newStatus === "Processed") {
                            statusElement.classList.add("badge-primary");
                        } else if (newStatus === "Rejected") {
                            statusElement.classList.add("badge-danger");
                        } else {
                            statusElement.classList.add("badge-warning");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong.');
                    });
            });
        });
    });
</script>
