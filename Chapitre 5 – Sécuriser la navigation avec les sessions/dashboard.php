<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
echo "<h1>Bienvenue " . $_SESSION['user'] . " !</h1>";
echo "<a href='logout.php'>Se déconnecter</a>";