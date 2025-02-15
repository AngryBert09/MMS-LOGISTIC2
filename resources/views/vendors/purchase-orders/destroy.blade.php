 <!-- Delete Action: Delete only if order is in a cancellable state -->
 @if ($order->order_status !== 'Completed' && $order->order_status !== 'Rejected')
     <a class="dropdown-item" href="{{ route('purchase-orders.destroy', $order->po_id) }}"
         onclick="event.preventDefault(); document.getElementById('delete-form-{{ $order->po_id }}').submit();">
         <i class="dw dw-delete-3"></i> Delete
     </a>
     <!-- Hidden form to handle the delete request -->
     <form id="delete-form-{{ $order->po_id }}" action="{{ route('purchase-orders.destroy', $order->po_id) }}"
         method="POST" style="display: none;">
         @csrf
         @method('DELETE')
     </form>
 @endif
