<?php

function redirect($location) {
    header("Location: $location");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isCustomer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

?>