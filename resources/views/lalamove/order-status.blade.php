<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
</head>

<body>
    <h1>Order Status</h1>

    @if (isset($status['error']))
        <p style="color: red;">Error: {{ $status['error'] }}</p>
    @else
        <pre>{{ json_encode($status, JSON_PRETTY_PRINT) }}</pre>
    @endif

    <a href="{{ route('lalamove.create.order') }}">Create Another Order</a>
</body>

</html>
