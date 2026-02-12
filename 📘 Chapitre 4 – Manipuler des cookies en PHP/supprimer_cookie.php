<?php
setcookie("utilisateur", "", time() - 3600, "/");
echo "Cookie 'utilisateur' supprimé.";