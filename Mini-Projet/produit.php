<?php
session_start();

$produits = json_decode(file_get_contents("tableaux.json"), true);

if(!isset($_SESSION["panier"]))
{
    $_SESSION["panier"] = [];
}

$erreur = "";

if(isset($_POST["ok"]))
{
    $nom = $_POST["produit"];
    $qte = $_POST["quan"];

    foreach($produits as $p)
    {
        if($p["nom"] == $nom)
        {
            if($qte <= $p["stock"])
            {
                $_SESSION["panier"][] = 
                [
                    "nom"=>$p["nom"],
                    "prix"=>$p["prix"],
                    "qte"=>$qte
                ];
            }
            else
            {
                $erreur = "Stock insuffisant";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Produits</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Acheter un produit</h2>

<form method="POST">
<select name="produit">
<option value="">choisir un produit</option>
<option value="Téléphone">Téléphone</option>
<option value="Casque">Casque</option>
<option value="Tablette">Tablette</option>
<option value="Montre connectée">Montre connectée</option>
</select>

<input type="number" name="quan" placeholder="Quantité">

<button type="submit" name="ok">BUY</button>
</form>

<h3>Panier</h3>

<table>
<tr>
<th>Produit</th>
<th>Quantité</th>
<th>Prix</th>
</tr>

<?php
foreach($_SESSION["panier"] as $p)
{
echo "<tr>";
echo "<td>".$p["nom"]."</td>";
echo "<td>".$p["qte"]."</td>";
echo "<td>".$p["prix"]."</td>";
echo "</tr>";
}
?>

</table><br>

<a href="facture.php">Visualiser la facture</a>

</body>
</html>