<?php
setcookie("utilisateur", "Alice", time() + 3600, "/");
echo "Cookie 'utilisateur' créé pour 1 heure.";