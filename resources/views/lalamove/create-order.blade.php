<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Lalamove Order</title>
</head>

<body>
    <h1>Create Lalamove Order</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('lalamove.create.order.submit') }}" method="POST">
        @csrf
        <label for="pickup_location">Pickup Location:</label>
        <input type="text" name="pickup_location" id="pickup_location" required><br>

        <label for="dropoff_location">Dropoff Location:</label>
        <input type="text" name="dropoff_location" id="dropoff_location" required><br>

        <label for="item_description">Item Description:</label>
        <input type="text" name="item_description" id="item_description" required><br>

        <button type="submit">Create Order</button>
    </form>
</body>

</html>
