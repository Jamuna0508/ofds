<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Fuel Delivery</title>
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header>
    <div class="container">
        <div class="logo-container">
            <img src="logo.png" alt="Gasoline Gurus Logo" class="logo">
            <p>ONLINE FUEL DELIVERY SYSTEM</p>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">HOME</a></li>
                <li><a href="contact.html">CONTACT US</a></li>
                <li><a href="customer/login.php">LOGIN</a></li>
                <li><a href="customer/signup.php">SIGN UP</a></li>
                <li><a href="customer/customer_dashboard.php">DASHBOARD</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Hero section -->
<section class="hero">
    <div class="container">
        <h1>Effortless Fuel Delivery, Right to Your Doorstep</h1>
        <p>Order fuel online and get it delivered wherever you are.</p>
        <a href="customer/customer_order_form.php" class="cta-btn">Order Now</a>
    </div>
</section>

<!-- About section -->
<section id="about" class="about">
    <div class="container">
        <h1>About Gasoline Gurus</h1>
        <div class="content">
            <p>GASOLINE GURUS is your trusted partner in convenient and reliable fuel delivery. With a commitment to innovation and customer satisfaction, we are revolutionizing the way you refuel.</p>
            <p>Our mission is to provide seamless access to fuel through our user-friendly online platform. Whether you're at home, work, or on the road, Gasoline Gurus ensures that you never run out of fuel when you need it most.</p>
            <p>Why choose Gasoline Gurus?</p>
        </div>
        <ul>
            <li>
                <strong>Convenience:</strong>
                <p>Order fuel anytime, anywhere with our easy-to-use mobile app or website.</p>
            </li>
            <li>
                <strong>Variety:</strong>
                <p>We offer a wide range of fuel types including gasoline, diesel, and biodiesel, ensuring there's a solution for every vehicle and need.</p>
            </li>
            <li>
                <strong>Flexibility:</strong>
                <p>Choose between scheduled deliveries or on-demand service, tailored to fit your schedule.</p>
            </li>
            <li>
                <strong>Reliability:</strong>
                <p>Our delivery process is safe, efficient, and compliant with all regulatory standards, ensuring peace of mind with every order.</p>
            </li>
            <li>
                <strong>Commitment:</strong>
                <p>At Gasoline Gurus, customer satisfaction is our priority. We strive to exceed your expectations with every interaction.</p>
            </li>
        </ul>
        <p>Join thousands of satisfied customers who rely on Gasoline Gurus for their fuel needs. Experience the future of refueling today!</p>
    </div>
</section>

<!-- Services section -->
<section class="services elegant-theme" id="services">
    <div class="container">
        <h2>Our Services</h2>
        <div class="services-grid">
            <div class="service">
                <i class="fas fa-gas-pump"></i>
                <h3>Various Fuel Types</h3>
                <p>We offer a range of fuel types including gasoline, diesel, and biodiesel.</p>
            </div>
            <div class="service">
                <i class="fas fa-truck"></i>
                <h3>Flexible Delivery</h3>
                <p>Choose between scheduled or on-demand deliveries to fit your needs.</p>
            </div>
            <div class="service">
                <i class="fas fa-shield-alt"></i>
                <h3>Safe and Secure</h3>
                <p>Our delivery process prioritizes safety and follows all necessary regulations.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our products section -->
<section class="our-products elegant-theme" id="our-products">
    <div class="container">
        <h2>Our Products</h2>
        <div class="fuel-prices">
            <div class="fuel-type">
                <img src="g.jpg" alt="Gasoline" class="fuel-image">
                <h3>Gasoline</h3>
                <p>RS.98 per litre</p>
            </div>
            <div class="fuel-type">
                <img src="d.jpg" alt="Diesel" class="fuel-image">
                <h3>Diesel</h3>
                <p>RS.90 per litre</p>
            </div>
            <div class="fuel-type">
                <img src="p.jpg" alt="Petrol" class="fuel-image">
                <h3>Petrol</h3>
                <p>RS.98 per litre</p>
            </div>
            <div class="fuel-type">
                <img src="c.jpg" alt="CNG" class="fuel-image">
                <h3>CNG</h3>
                <p>RS.77 per litre</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQs section -->
<section class="faq-section elegant-theme" id="FAQs">
    <div class="container">
        <h2>Frequently Asked Questions</h2>
        <div class="faq">
            <input type="checkbox" id="question1">
            <label for="question1">How can I place an order?</label>
            <div class="answer">
                <p>To place an order, simply navigate to our website or mobile app, select the desired fuel type and quantity, and proceed with the checkout process.</p>
            </div>
        </div>
        <div class="faq">
            <input type="checkbox" id="question2">
            <label for="question2">What are the delivery options?</label>
            <div class="answer">
                <p>We offer both scheduled and on-demand delivery options. You can choose the most convenient option based on your requirements.</p>
            </div>
        </div>
        <div class="faq">
            <input type="checkbox" id="question3">
            <label for="question3">Is it safe to order fuel online?</label>
            <div class="answer">
                <p>Yes, ordering fuel online is safe. We follow strict safety protocols and regulations to ensure that the delivery process is secure and reliable.</p>
            </div>
        </div>
    </div>
</section>

<!-- Admin Module Section -->
<section class="module">
    <div class="container">
        <h2>Admin Module</h2>
        <p>Manage orders, fuel stations, delivery agents.</p>
        <a href="admin/admin_dashboard.php" class="btn">Admin Dashboard</a>
    </div>
</section>

<!-- Fuel Station Module Section -->
<section class="module">
    <div class="container">
        <h2>Fuel Station Module</h2>
        <p>Add, view, and manage fuel stations.</p>
        <a href="fuelstation/add_fuel_station.php" class="btn">Manage Fuel Stations</a>
    </div>
</section>

<!-- Delivery Agent Module Section -->
<section class="module">
    <div class="container">
        <h2>Delivery Agent Module</h2>
        <p>Add, view, and manage delivery agents.</p>
        <a href="deliveryagent/add_delivery_agent.php" class="btn">Manage Delivery Agents</a>
    </div>
</section>

<!-- Footer section -->
<footer class="elegant-theme">
    <div class="container">
        <p>&copy; 2024 Gasoline Gurus. All rights reserved.</p>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<!-- Link to JavaScript files if needed -->
<script src="js/script.js"></script>

</body>
</html>
