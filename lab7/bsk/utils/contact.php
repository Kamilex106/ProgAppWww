<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'] ?? null;
    $message = $data['message'] ?? null;
    $token = $data['token'] ?? null;

    if (!$email || !$message || !$token) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    $recaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$captchaSecret.'&response='.$token), true);

    if ($recaptcha == false) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to connect to reCAPTCHA API"]);
        exit;
    }

    $score = $recaptcha['score'];
    if($recaptcha['success'] === true && $score >= 0.5 && $recaptcha['action'] == "contact") {
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $conn->real_escape_string($email);
        $message = $conn->real_escape_string($message);

        $sql = "INSERT INTO contact_messages (email, message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        $bind_result = $stmt->bind_param("ss", $email, $message);
        if ($bind_result === false) {
            die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'score' => $score]);
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    } elseif ($score < 0.5) {
        http_response_code(403); // Forbidden 
        echo json_encode(['status' => 'error', 'message' => "Access denied: Bot activity detected", 'score' => $score]);
    } else {
        http_response_code(403); // Forbidden
        echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>