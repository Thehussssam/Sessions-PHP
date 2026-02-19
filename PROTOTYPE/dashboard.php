<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html>
<body>

<h2>
<?php
echo "Bienvenue " . $user["name"] . " - Rôle : " . $user["role"];
?>
</h2>

<a href="logout.php">Se déconnecter</a>

</body>
</html>