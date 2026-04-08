<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';

    if ($nom === "admin" && $motdepasse === "1234") {
        session_regenerate_id(true);
        $_SESSION['user'] = $nom;
        header('Location: dashboard.php');
        exit;
    } else {
        $message = "Identifiants incorrects.";
    }
}
?>
<form method="POST">
    <label>Nom :</label>
    <input type="text" name="nom">
    <label>Mot de passe :</label>
    <input type="password" name="motdepasse">
    <button type="submit">Se connecter</button>
</form>
<?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>