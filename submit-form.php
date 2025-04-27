<?php
// submit-form.php

// Configuration
date_default_timezone_set('Europe/Paris');
$recipient_email = 'foucher.nicolas.44@gmail.com';
$site_name = 'Enlèvement-Épave-44';
$thank_you_page = false; // Afficher le formulaire de confirmation directement

// Fonction de nettoyage
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Initialisation des variables
typename: string \$name = '';
typename: string \$phone = '';
typename: string \$message = '';
\$errors = [];

if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et sanitation
    \$name = sanitize_input(\$_POST['name'] ?? '');
    \$phone = sanitize_input(\$_POST['phone'] ?? '');
    \$message = sanitize_input(\$_POST['message'] ?? '');

    // Validation
    if (empty(\$name)) {
        \$errors[] = 'Le nom est requis.';
    }
    if (empty(\$phone)) {
        \$errors[] = 'Le téléphone est requis.';
    } elseif (!preg_match('/^[0-9 \+\-]{6,20}\$/', \$phone)) {
        \$errors[] = 'Le format du téléphone est invalide.';
    }

    // Si pas d'erreurs, envoi du mail
    if (empty(\$errors)) {
        \$subject = "Nouveau message depuis \$site_name";
        \$body = "Vous avez reçu un nouveau message via le formulaire de contact :\n\n" .
                "Nom : \$name\n" .
                "Téléphone : \$phone\n" .
                "Message :\n\$message\n\n" .
                "Envoyé le : " . date('d/m/Y H:i') . "\n";
        \$headers = 'From: noreply@enlevement-epave-44.fr' . "\r\n" .
                   'Reply-To: '. \$phone . ' <noreply@enlevement-epave-44.fr>' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        
        mail(\$recipient_email, \$subject, \$body, \$headers);
        
        // Afficher page de remerciement
        header('Location: thank-you.html');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Erreur formulaire - Enlèvement-Épave-44</title>
  <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <a href="index.html" class="logo">🚛 Enlèvement-Épave-44</a>
      <nav class="main-nav">
        <ul>
          <li><a href="index.html#services">Services</a></li>
          <li><a href="index.html#avantages">Avantages</a></li>
          <li><a href="index.html#contact">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <h1>Oups, il y a eu un problème !</h1>
    <?php if (!empty(\$errors)): ?>
      <div class="advantages">
        <ul>
          <?php foreach (\$errors as \$error): ?>
            <li style="background: #ffecec; color: #d8000c; border-color: #d8000c;"><?php echo \$error; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <p>Merci de <a href="index.html#contact">revenir au formulaire</a> et corriger les champs.</p>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 Enlèvement-Épave-44. Tous droits réservés.</p>
      <nav>
        <ul>
            <li>
                <a href="politique-cookies.html">
                  <img src="assets/politique-cookies.png" alt="Icone Politique de Cookies"
                       style="width:70px; vertical-align:middle; margin-right:8px;">
                  Politique de Cookies
                </a>
              </li>
          <li>
            <a href="mentions-legales.html">
              <img src="assets/mentions-legales.png" alt="Icone Mentions Légales"
                   style="width:70px; vertical-align:middle; margin-right:8px;">
              Mentions légales
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </footer>
</body>
</html>
