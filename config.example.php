<?php
session_start();

$conn = new mysqli(
    "YOUR_DATABASE_HOST",
    "YOUR_DATABASE_USERNAME",
    "YOUR_DATABASE_PASSWORD",
    "YOUR_DATABASE_NAME"
);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$BLOOD_GROUPS = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];

function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}