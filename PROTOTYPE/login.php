<?php
session_start();

$users = [
    ["name" => "Ahmed", "password" => "admin123", "role" => "administrateur", "active" => true],
    ["name" => "Sara", "password" => "pass456", "role" => "formateur", "active" => true],
    ["name" => "Leila", "password" => "test789", "role" => "apprenant", "active" => false],
    ["name" => "Alae", "password" => "test309", "role" => "apprenant", "active" => true]
];

$message = "";

if (isset($_POST["username"]) && isset($_POST["password"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $found = false;

    foreach ($users as $user) {

        if ($user["name"] == $username && $user["password"] == $password) {

            $found = true;

            if ($user["active"] == false) {
                $message = "Compte désactivé";
            } else {
                $_SESSION["user"] = $user;
                header("Location: dashboard.php");
                exit();
            }
        }
    }

    if ($found == false) {
        $message = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>

<h2>Connexion</h2>

<form method="POST">
    Nom: <input type="text" name="username"><br><br>
    Mot de passe: <input type="password" name="password"><br><br>
    <button type="submit">Se connecter</button>
</form>

<p><?php echo $message; ?></p>

</body>
</html>