<?php
// Simple authentication logic (you should implement a more secure authentication system)
$adminUsername = 'admin';
$adminPassword = 'password';

function isAuthenticated() {
    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
        return true;
    }

    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === $GLOBALS['adminUsername'] && $password === $GLOBALS['adminPassword']) {
            $_SESSION['authenticated'] = true;
            return true;
        }
    }

    return false;
}