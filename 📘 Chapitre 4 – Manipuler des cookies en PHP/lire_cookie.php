<?php
if (isset($_COOKIE['utilisateur'])) {
    echo "Bonjour " . $_COOKIE['utilisateur'];
} else {
    echo "Aucun cookie trouvé.";
}