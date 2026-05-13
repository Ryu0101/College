<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
    <style>
        /* Dark theme styles */
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Contact form container */
        .contact-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            text-align: center;
            width: 350px;
        }

        /* Form fields */
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #2a2a2a;
            border: none;
            color: #ffffff;
            border-radius: 5px;
        }

        /* Button styling */
        button {
            background-color: #ff5722;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

    <div class="contact-container">
        <h2>Contact Me</h2>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit" name="submit">Send Message</button>
        </form>
        <p><?php echo $response ?? ''; ?></p>
    </div>

</body>
</html>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    // Simple validation
    if (!empty($name) && !empty($email) && !empty($message)) {
        $response = "Thank you, $name! Your message has been received.";
    } else {
        $response = "Please fill in all fields!";
    }
}
?>
