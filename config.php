<?php
session_start();

$conn = new mysqli(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASSWORD"),
    getenv("DB_NAME")
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
