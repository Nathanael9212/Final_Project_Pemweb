<?php
// includes/auth.php

function isLoggedIn() {
    return isset($_SESSION['email']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
