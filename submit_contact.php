<?php
if (
    (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    || (!isset($_POST['message']) || empty($_POST['message']))
    )
{
	echo('Il faut un email et un message valides pour soumettre le formulaire.');
    return;
}

$email = $_POST['email'];
$message = $_POST['message'];
$screenshotIsValid = false;

if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 1)
{
	echo('Le chargement a échoué, veuillez réessayer ou réduire la taille de votre fichier à moins de 1Mo.');
    return;
}

if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 0)
{
        // Testons si le fichier n'est pas trop gros
        if ($_FILES['screenshot']['size'] > 10000000) {
            echo "Veuillez réduire la taille de votre fichier à moins de 1Mo et réessayer.";
            return;
        }
        else {
                // Testons si l'extension est autorisée
                $fileInfo = pathinfo($_FILES['screenshot']['name']);
                $extension = $fileInfo['extension'];
                $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
                if (!in_array($extension, $allowedExtensions)) {
                    echo "Le fichier n'a pas été pris en charge. Les formats pris en compte sont : JPEG, GIF ou PNG.";
                    return;
                }
                else {
                        // On peut valider le fichier et le stocker définitivement
                        move_uploaded_file($_FILES['screenshot']['tmp_name'], 'uploads/' . basename($_FILES['screenshot']['name']));
                        $screenshotIsValid = true;
                }
        }
}

$screenshot = $_FILES['screenshot']['name'];

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Site de Recettes - Demande de contact reçue</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
            rel="stylesheet"
        >
    </head>
    <body>
        <div class="container">
    
        <?php include_once('header.php'); ?>
            <h1>Message bien reçu !</h1>
            
            <div class="card">
                
                <div class="card-body">
                    <h5 class="card-title">Rappel de vos informations</h5>
                    <p class="card-text"><b>Email</b> : <?php echo($email); ?></p>
                    <p class="card-text"><b>Message</b> : <?php echo strip_tags($message); ?></p>
                    <?php if ($screenshotIsValid): ?>
                        <p class="card-text"><b>Pièce jointe</b> : <?php echo strip_tags($screenshot); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>