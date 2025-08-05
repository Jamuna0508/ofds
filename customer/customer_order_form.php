<?php
session_start();
include('auth_check.php'); // Ensure user is logged in

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch fuel types and prices from database
$sql_fuel_types = "SELECT fuel_type, price_per_litre FROM fuel_prices";
$result_fuel_types = $conn->query($sql_fuel_types);
$fuel_types = [];

if ($result_fuel_types->num_rows > 0) {
    while ($row = $result_fuel_types->fetch_assoc()) {
        $fuel_types[] = $row;
    }
} else {
    echo "No fuel types found.";
}

// Fetch fuel stations from database
$sql_fuel_stations = "SELECT station_id, fs_name, address FROM fuel_stations";
$result_fuel_stations = $conn->query($sql_fuel_stations);
$fuel_stations = [];

if ($result_fuel_stations->num_rows > 0) {
    while ($row = $result_fuel_stations->fetch_assoc()) {
        $fuel_stations[] = $row;
    }
} else {
    echo "No fuel stations found.";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Validate and sanitize input data (you should add proper validation/sanitization)
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $shipping_address = $_POST['shipping_address'];
    $fuel_type = $_POST['fuel_type'];
    $quantity = $_POST['quantity'];
    $station_id = $_POST['station_id'];
    $payment_method = $_POST['payment_method'];
    $total_price = $_POST['total_price'];
    $payment_status = $_POST['payment_status'];
    
    // Additional fields for payment details (only capturing, not processing)
    $cc_number = isset($_POST['cc_number']) ? $_POST['cc_number'] : null;
    $cc_expiry = isset($_POST['cc_expiry']) ? $_POST['cc_expiry'] : null;
    $cc_cvc = isset($_POST['cc_cvc']) ? $_POST['cc_cvc'] : null;
    $dc_number = isset($_POST['dc_number']) ? $_POST['dc_number'] : null;
    $dc_expiry = isset($_POST['dc_expiry']) ? $_POST['dc_expiry'] : null;
    $dc_cvc = isset($_POST['dc_cvc']) ? $_POST['dc_cvc'] : null;
    $upi_id = isset($_POST['upi_id']) ? $_POST['upi_id'] : null;

    // Insert order into database (replace with your actual database insert logic)
    $stmt = $conn->prepare("INSERT INTO orders (name, phone, email, shipping_address, fuel_type, quantity, station_id, payment_method, total_price, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if prepare() succeeded
    if ($stmt === false) {
        die('Prepare Error: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssssiisss", $name, $phone, $email, $shipping_address, $fuel_type, $quantity, $station_id, $payment_method, $total_price, $payment_status);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Your order was placed successfully!";
        $stmt->close();
        header("Location: customer_dashboard.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error placing your order. Please try again.";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Fuel Delivery Order Form</title>
    <style>
        /* General styling */
        body {
            background-image: url('bk.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            background-image: url('cofc.jpg');
            background-size: cover;
            width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #333;
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea,
        select,
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            font-size: 14px;
        }

        textarea {
            height: 80px;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 30px; /* to display custom arrow in select */
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"><path fill="%23333" d="M7 8L0.3 0.3 0 0.6l7 7 7-7-.3-.3z"/></svg>') no-repeat right #fff;
            background-size: 14px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        #total_price {
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }

        #order_summary {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            input[type="text"],
            input[type="email"],
            textarea,
            select,
            input[type="number"] {
                font-size: 12px;
            }
        }

        .payment-method-details {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        // Function to calculate total price based on fuel type and quantity
        function calculateTotalPrice() {
            const fuelPrices = {
                <?php foreach ($fuel_types as $fuel): ?>
                    '<?php echo htmlspecialchars($fuel['fuel_type']); ?>': <?php echo $fuel['price_per_litre']; ?>,
                <?php endforeach; ?>
            };

            const fuelType = document.querySelector('select[name="fuel_type"]').value;
            const quantity = parseFloat(document.querySelector('input[name="quantity"]').value);

            if (fuelType && !isNaN(quantity) && quantity > 0) {
                const pricePerUnit = fuelPrices[fuelType];
                const totalPrice = pricePerUnit * quantity;
                document.getElementById('total_price').textContent = 'Total Price: ' + totalPrice.toFixed(2) + ' INR';
                document.querySelector('input[name="total_price"]').value = totalPrice.toFixed(2);

                // Update order summary if needed
                document.getElementById('order_summary').innerHTML = `
                    <h3>Order Summary:</h3>
                    <p><strong>Fuel Type:</strong> ${fuelType}</p>
                    <p><strong>Quantity:</strong> ${quantity} litres</p>
                    <p><strong>Total Price:</strong> ${totalPrice.toFixed(2)} INR</p>
                `;
            } else {
                document.getElementById('total_price').textContent = '';
                document.querySelector('input[name="total_price"]').value = '';
                document.getElementById('order_summary').innerHTML = '';
            }
        }

        // Function to get the user's current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        getAddressFromCoordinates(position.coords.latitude, position.coords.longitude);
                    },
                    function(error) {
                        console.error('Error getting current location:', error);
                        alert('Error fetching your current location. Please enter shipping address manually.');
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser. Please enter shipping address manually.');
            }
        }

        // Function to fetch address from coordinates and update shipping address field
        function getAddressFromCoordinates(latitude, longitude) {
            const address = `Your current address: Latitude ${latitude}, Longitude ${longitude}`;
            document.getElementById('shipping_address').value = address;
        }

        // Function to filter fuel stations based on address search
        function filterFuelStations() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('searchFuelStation');
            filter = input.value.toUpperCase();
            ul = document.getElementById("fuelStationList");
            li = ul.getElementsByTagName('li');

            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }

        // Function to set selected fuel station based on user click
        function selectFuelStation(stationId) {
            // Set the hidden input value for station_id
            document.getElementById('station_id').value = stationId;
            
            // Optionally, you can update the displayed selection for better user feedback
            const fuelStations = <?php echo json_encode($fuel_stations); ?>;
            const selectedStation = fuelStations.find(station => station.station_id === stationId);
            if (selectedStation) {
                document.getElementById('selectedStationDisplay').textContent = selectedStation.fs_name + ' - ' + selectedStation.address;
            }
        }

        // Function to show the corresponding payment method form
        function showPaymentMethodForm() {
            const paymentMethod = document.querySelector('select[name="payment_method"]').value;
            const paymentForms = document.querySelectorAll('.payment-method-details');

            paymentForms.forEach(form => {
                form.style.display = 'none';
            });

            if (paymentMethod) {
                document.getElementById(paymentMethod.toLowerCase().replace(/\s/g, '_') + '_details').style.display = 'block';
            }
        }

        // Function to update the payment status based on the selected payment method
        function updatePaymentStatus() {
            const paymentMethod = document.querySelector('select[name="payment_method"]').value;
            const paymentStatusField = document.querySelector('input[name="payment_status"]');

            if (paymentMethod === 'Cash on Delivery') {
                paymentStatusField.value = 'Not Paid';
            } else {
                paymentStatusField.value = 'Paid';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Online Fuel Delivery Order Form</h2>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="shipping_address">Shipping Address:</label>
            <textarea id="shipping_address" name="shipping_address" rows="4" required></textarea>
            <button type="button" onclick="getCurrentLocation()">Use Current Location</button>

            <label for="fuel_type">Fuel Type:</label>
            <select id="fuel_type" name="fuel_type" onchange="calculateTotalPrice()" required>
                <option value="">Select Fuel Type</option>
                <?php foreach ($fuel_types as $fuel): ?>
                    <option value="<?php echo htmlspecialchars($fuel['fuel_type']); ?>">
                        <?php echo htmlspecialchars($fuel['fuel_type']) . " (" . htmlspecialchars($fuel['price_per_litre']) . " INR per litre)"; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Quantity (in litres):</label>
            <input type="number" id="quantity" name="quantity" min="1" onchange="calculateTotalPrice()" required>

            <label for="fuel_station">Fuel Station:</label>
            <input type="text" id="searchFuelStation" onkeyup="filterFuelStations()" placeholder="Search for fuel station..">
            <input type="hidden" id="station_id" name="station_id">
            <ul id="fuelStationList">
                <?php foreach ($fuel_stations as $station): ?>
                    <li>
                        <a href="#" onclick="selectFuelStation('<?php echo htmlspecialchars($station['station_id']); ?>'); return false;">
                            <?php echo htmlspecialchars($station['fs_name'] . ' - ' . $station['address']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="selectedStationDisplay"></div>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" onchange="showPaymentMethodForm(); updatePaymentStatus();" required>
                <option value="">Select Payment Method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="UPI">UPI</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>

            <div id="credit_card_details" class="payment-method-details">
                <h3>Credit Card Details</h3>
                <label for="cc_number">Card Number:</label>
                <input type="text" id="cc_number" name="cc_number">
                <label for="cc_expiry">Expiry Date:</label>
                <input type="text" id="cc_expiry" name="cc_expiry">
                <label for="cc_cvc">CVC:</label>
                <input type="text" id="cc_cvc" name="cc_cvc">
            </div>

            <div id="debit_card_details" class="payment-method-details">
                <h3>Debit Card Details</h3>
                <label for="dc_number">Card Number:</label>
                <input type="text" id="dc_number" name="dc_number">
                <label for="dc_expiry">Expiry Date:</label>
                <input type="text" id="dc_expiry" name="dc_expiry">
                <label for="dc_cvc">CVC:</label>
                <input type="text" id="dc_cvc" name="dc_cvc">
            </div>

            <div id="upi_details" class="payment-method-details">
                <h3>UPI Details</h3>
                <label for="upi_id">UPI ID:</label>
                <input type="text" id="upi_id" name="upi_id">
            </div>

            <input type="hidden" name="total_price">
            <input type="hidden" name="payment_status">
            <div id="total_price"></div>
            <div id="order_summary"></div>

            <input type="submit" name="submit" value="Place Order">
        </form>
    </div>
</body>
</html>
