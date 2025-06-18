<?php
session_start();

// Initialize the session if not set
if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 0;
}

// Reset session if cancel is clicked
if (isset($_POST['cancel'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Flow logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// If dialing *312#
if (isset($_POST['ussd_input']) && trim($_POST['ussd_input']) === '*312#') {
$_SESSION['step'] = 1; // Main Menu
} 
    
// Handling user replies and advancing steps
elseif (isset($_POST['reply'])) {
if ($_SESSION['step'] == 1 && $_POST['reply'] == '1') {
$_SESSION['step'] = 2; // Data Plans
} 
elseif ($_SESSION['step'] == 2 && $_POST['reply'] == '3') {
$_SESSION['step'] = 3; // Monthly Plans
} 
elseif ($_SESSION['step'] == 3 && $_POST['reply'] == '7') {
$_SESSION['step'] = 5; // 20GB + 4GB All Night Plan
}
// One-off Purchase selected
elseif ($_SESSION['step'] == 5 && $_POST['reply'] == '2') {
$_SESSION['step'] = 6; // One-off Purchase failed message
}
// Handling Back button
elseif ($_SESSION['step'] == 6 && $_POST['reply'] == '0') {
$_SESSION['step'] = 5; // Go back to 20GB + 4GB All Night Plan
}
}
}

// Set flags for modal visibility based on current step
$show_main = $_SESSION['step'] == 1;
$show_data = $_SESSION['step'] == 2;
$show_monthly_plans = $_SESSION['step'] == 3;
$show_20GB_plus = $_SESSION['step'] == 5;
$show_one_off_purchase_failed = $_SESSION['step'] == 6;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MTN USSD Simulator</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                background-image: url('image/mtn wall.jpg'); /* Adjust the path */
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100vh;
                margin: 0;
                padding: 0;
            }
            .logo {
                width: 80px; /* Adjust the size of the logo */
                height: auto;
                margin-right: 10px;
                animation: glow 1.5s ease-in-out infinite alternate;
            }

            .title-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* align left if needed */
            margin-bottom: 100px;
            }

            .container {
                max-width: 500px; /* Set a maximum width for the container */
                margin: 0 auto; /* Center the container */
            }

            .input-container {
            text-align: center;
            }

            /* Glowing Effect for Image */
            @keyframes glow {
            0% {
                box-shadow: 0 0 5px #ff0000, 0 0 10px #ff0000, 0 0 20px #ff0000, 0 0 30px #ff0000;
            }
            100% {
                box-shadow: 0 0 5px #00ff00, 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;
            }
            }

            .form-control {
            width: 100%; /* Make form control take full width */
            max-width: 300px; /* Limit the width for the input */
            margin: 0 auto; /* Center the input */
            }

            .modal-body {
            padding: 20px;
            text-align: center;
            }

            .btn-block {
            width: 100%;
            max-width: 300px; /* Limit button width */
            margin: 10px auto;
            }

             /* Revert list styling to original */
            ul {
            list-style-type: none;
            padding-left: 0;
            }

            li {
            padding: 8px 0;
            font-size: 16px;
            }
            /* Glowing Effect for Image */
            @keyframes glow {
            0% {
            box-shadow: 0 0 5px #ff0000, 0 0 10px #ff0000, 0 0 20px #ff0000, 0 0 30px #ff0000;
            }
            100% {
            box-shadow: 0 0 5px #00ff00, 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;
            }
            }

            /* Adjust the modal size */
            .modal-dialog {
            max-width: 300px; /* Narrow width */
            width: 100%; /* Allow full width up to max-width */
            margin: 0 auto; /* Center the modal horizontally */
            }

            /* Ensure everything is aligned to the left */
            .modal-content {
            padding: 5px;
            text-align: left; /* Ensure text is aligned to the left */
            }
            h3 {
            color: #ffcc00;
            font-weight: bold;
            text-align: center;
            animation: glow 1.5s ease-in-out infinite alternate;
            }

            /* Ensure modal header text is aligned left */
            .modal-header {
            padding: 5px 10px; /* Tight header padding */
            border-bottom: 1px solid #ddd; /* Add slight border */
            font-size: 14px; /* Smaller header font */
            text-align: left; /* Make sure header content is aligned left */
            }

            /* Ensure modal body text is aligned left */
            .modal-body {
            padding: 8px 10px; /* Reduce body padding */
            font-size: 14px; /* Reduce body text size */
            text-align: left; /* Make sure body content is aligned left */
            }

            /* Ensure modal footer is left-aligned */
            .modal-footer {
            padding: 3px 10px; /* Tighten footer padding */
            border-top: 1px solid #ddd; /* Add slight border */
            text-align: left; /* Align footer content to the left */
            }

            /* List styling */
            ul {
            padding-left: 10px; /* Tighten list item space */
            margin-bottom: 5px; /* Reduce bottom margin */
            list-style-position: inside; /* Ensure list bullets are inside the box */
            }

            /* List item styling */
            li {
            font-size: 14px; /* Smaller font for list items */
            padding: 3px 0; /* Tighten space between list items */
            text-align: left; /* Ensure list items are aligned left */
            }

            /* Style the form inputs */
            input[type="text"] {
            font-size: 14px; /* Adjust text size */
            padding: 5px; /* Tighten input padding */
            width: 100%; /* Ensure input takes full width */
            margin-bottom: 5px; /* Tighten space below input */
            text-align: left; /* Ensure input text is aligned left */
            }

            /* Style the buttons */
            .btn {
            font-size: 12px; /* Smaller font size for buttons */
            padding: 5px 10px; /* Reduced button padding */
            width: 100%; /* Make buttons take full width */
            margin-bottom: 5px; /* Reduce space between buttons */
            text-align: left; /* Align buttons to the left */
            }

            /* Adjust the cancel button style */
            .btn-danger {
            margin-top: 5px; /* Ensure cancel button has a little spacing from other buttons */
            }
            .container {
            max-width: 500px;
            min-height: 400px;
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            color: #f1f1f1;
            box-shadow: 0 0 10px rgba(0,0,0,0.8);
            display: block; /* remove flex so everything stacks normally */
            }
            .modal-content {
            background-color: #2a2a2a; /* Dark modal background */
            color: #f1f1f1; /* Light text */
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            }
            .btn-yellow {
            background-color: #ffcc00;
            color: #000;
            font-weight: bold;
            border: none;
            padding: 6px 16px;
            white-space: nowrap;
            }

            .btn-yellow:hover {
            background-color: #e6b800;
            color: #000;
            }

            .modal-header, .modal-footer {
            border-color: #444;
            }
            .form-control {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            }

            input::placeholder {
            color: #ccc;
            }

            .btn {
            border: none;
            }
            .input-section {
            margin-top: 50px; /* adjust this value to control the space */
            margin-bottom: 40px;
            }

        </style>
    </head>
    <body>
        <h4 align="center" style="color:white;">ADEKUNLE BUSAYO F.</h4>
        <div class="container mt-5">

            <div class="title-wrapper">
             <img src="image/mtn.jpeg" class="logo"> <!-- Adjust path to logo -->
                <h3 class="text-center mb-0">MTN USSD Simulator</h3>
            </div>

            <div class="input-container"> <!-- This will push the input lower -->
                <!-- Example input or button -->
            </div>

            <!-- Initial Dial Input -->
            <?php if ($_SESSION['step'] == 0): ?>
                <div class="input-section mt-4">
                    <form method="POST">
                        <div class="d-flex">
                            <input type="text" name="ussd_input" class="form-control mr-2"  required>
                            <button type="submit" class="btn btn-yellow">Send</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main Menu -->
        <?php if ($show_main): ?>
            <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Main Menu</h5>
                        </div>
                        <div class="modal-body">
                            <ul>
                                <li>1. Data Plans</li>
                                <li>2. Special Offers</li>
                                <li>3. Social Bundles</li>
                                <li>4. Xtra Value</li>
                                <li>5. Business Plans</li>
                                <li>6. Roaming/Int'l Call</li>
                                <li>7. Borrow Credit/Recharge</li>
                                <li>8. Exit</li>
                            </ul>
                            <hr>
                             <form method="POST">
                                <div class="d-flex">
                                    <input type="text" name="reply" class="form-control mr-2" required>
                                    <button type="submit" class="btn btn-yellow">Send</button>
                                </div>
                            </form>

                            <!-- Cancel Button -->
                            <form method="POST" class="mt-2">
                                <button type="submit" name="cancel" class="btn btn-danger btn-block">Cancel</button>
                            </form>
                     </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Data Plans -->
        <?php if ($show_data): ?>
            <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Data Plans</h5>
                        </div>
                        <div class="modal-body">
                            <ul>
                                <li>1. Daily</li>
                                <li>2. Weekly</li>
                                <li>3. Monthly</li>
                                <li>4. 2-3 Months</li>
                                <li>5. Yearly</li>
                                <li>6. Family Packs</li>
                                <li>7. Back</li>
                            </ul>
                            <hr>
                            <form method="POST">
                                <div class="d-flex">
                                    <input type="text" name="reply" class="form-control mr-2" placeholder="Enter option e.g. 1" required>
                                    <button type="submit" class="btn btn-yellow">Send</button>
                                 </div>
                            </form>

                            <!-- Cancel Button -->
                            <form method="POST" class="mt-2">
                                <button type="submit" name="cancel" class="btn btn-danger btn-block">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Monthly Data Plans -->
        <?php if ($show_monthly_plans): ?>
            <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Monthly Data Plans</h5>
                        </div>
                        <div class="modal-body">
                        <ul>
                            <li>1. N1,500</li>
                            <li>2. N2,000 for 2.7GB</li>
                            <li>3. N3,500 for 7GB</li>
                            <li>4. N2,500 for 3.5GB</li>
                            <li>5. N4,500 for 10GB</li>
                            <li>6. N6,500 for 16.5GB</li>
                            <li>7. N7,500 for 20GB</li>
                            <li>99. Next</li>
                            <li>0. Back</li>
                        </ul>
                        <hr>
                        <form method="POST">
                            <div class="d-flex">
                                <input type="text" name="reply" class="form-control mr-2" placeholder="Enter option e.g. 1" required>
                                <button type="submit" class="btn btn-yellow">Send</button>
                            </div>          
                        </form>

                        <!-- Cancel Button -->
                        <form method="POST" class="mt-2">
                            <button type="submit" name="cancel" class="btn btn-danger btn-block">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 20GB + 4GB All Night Plan -->
        <?php if ($show_20GB_plus): ?>
            <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">20GB + 4GB All Night for N7,500</h5>
                        </div>
                        <div class="modal-body">
                            <ul>
                                <li>1. Auto-Renew</li>
                                <li>2. One-off</li>
                                <li>3. Buy for a Friend</li>
                                <li>4. Redeem Promo Code</li>
                                <li>0. Back</li>
                            </ul>
                            <hr>
                            <form method="POST">
                                <div class="d-flex">
                                    <input type="text" name="reply" class="form-control mr-2" placeholder="Enter option e.g. 1" required>
                                    <button type="submit" class="btn btn-yellow">Send</button>
                                </div>
                            </form>

                            <!-- Cancel Button -->
                            <form method="POST" class="mt-2">
                                <button type="submit" name="cancel" class="btn btn-danger btn-block">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- One-off Purchase Failed -->
        <?php if ($show_one_off_purchase_failed): ?>
            <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Activation Failed</h5>
                        </div>
                        <div class="modal-body">
                            <p>Activation of 20GB monthly plan failed due to insufficient balance. Click <a href="https://mtnapp.page.link/myMTNNGApp" target="_blank">here</a> to recharge, or dial *671# to buy airtime.</p>
                                 <ul>
                                    <li>99. Next</li>
                                    <li>0. Back</li>
                                </ul>
                            <hr>
                            <form method="POST">
                                <div class="d-flex">
                                    <input type="text" name="reply" class="form-control mr-2" placeholder="Enter option e.g. 1" required>
                                    <button type="submit" class="btn btn-yellow">Send</button>
                                </div>
                            </form>

                            <!-- Cancel Button -->
                            <form method="POST" class="mt-2">
                                <button type="submit" name="cancel" class="btn btn-danger btn-block">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>