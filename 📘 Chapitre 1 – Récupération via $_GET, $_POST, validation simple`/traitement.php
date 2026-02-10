<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);

    if (empty($nom) || empty($email)) {
        echo "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide.";
    } else {
        echo "Bonjour $nom, votre email est $email.";
    }
}
