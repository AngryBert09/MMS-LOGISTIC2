<div class="container">
    <h1>PayPal Checkout</h1>
    <form action="{{ route('paypal.checkout') }}" method="GET">
        <button type="submit" class="btn btn-primary">Pay with PayPal</button>
    </form>
</div>
