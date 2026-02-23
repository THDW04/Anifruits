<?php
require_once(__DIR__ . '/../classes/Connexion.php');
require_once(__DIR__ . '/../classes/Manager.php');
require_once(__DIR__ . '/../classes/Personnage.php');

session_start();

$db = PDOFactory::getMysqlConnexion();
$manager = new personnageManager($db);
$anifruits = $manager->getAll();

//==== GESTION DES PERSO ====//
//Récupère un perso
if (isset($_GET['id']) || isset($_POST['id'])) {
    $id = $_GET['id'] ?? $_POST['id'];
    $anifruit = $manager->getAnifruitById($id);
}

//Ajouter un perso
if (isset($_POST['add'])) {
    if (!empty($_FILES['img']['name'])) {
        $img = $_FILES['img']['name'];
        $tmpName = $_FILES['img']['tmp_name'];
        move_uploaded_file($tmpName, "../assets/img/" . $img);
    }

    $manager->add($_POST['name'], $_POST['pv'], $_POST['atk'], $_POST['specialName'], $_POST['specialMulti'], $_POST['specialType'], $img);

    header('Location: liste.php');
    exit();
}

//Modifier un perso
if (isset($_POST['update'])) {
    $anifruit->setName($_POST['name']);
    $anifruit->setPv($_POST['pv']);
    $anifruit->setAtk($_POST['atk']);
    $anifruit->setSpecialName($_POST['specialName']);
    $anifruit->setSpecialType($_POST['specialType']);
    $anifruit->setSpecialMulti($_POST['specialMulti']);

    $manager->update($anifruit);
    header('Location: liste.php');
    exit();
}

//Supp un perso
if (isset($_POST['delete'])) {
    $manager->sup((int) $_POST['id']);
    header('Location: liste.php');
    exit();
}


//===== SYSTÈME DE COMBAT ==== //
//Initialisation de la session
if (!isset($_SESSION['arene'])) {
    $_SESSION['arene'] = [];
}

//Récup les persos choisis
if (isset($_POST['choose'])) {
    //Max 2 perso choisi
    if (count($_SESSION['arene']) < 2) {
        array_push($_SESSION['arene'], $_POST['id-perso']);
    }
    //Avec un id unique
    $_SESSION['arene'] = array_unique($_SESSION['arene']);
}

if (
    isset($_POST['choose']) &&
    count($_SESSION['arene']) === 2 &&
    !isset($_SESSION['combat'])
) {
    $perso1 = $manager->getAnifruitById($_SESSION['arene'][0]);
    $perso2 = $manager->getAnifruitById($_SESSION['arene'][1]);

    $_SESSION['combat'] = [
        'perso1' => $perso1,
        'perso2' => $perso2,
        'termine' => false,
        'logs' => []
    ];
}

if (isset($_SESSION['combat'])) {
    $areneObjets = [$_SESSION['combat']['perso1'], $_SESSION['combat']['perso2']];
}

//Fonction qui gère les tours de combat
//Les deux attaquent simultanément
function jouerTour(string $action)
{
    //Récupérer l’état du combat depuis la session
    $combat = $_SESSION['combat'];

    //Assigner les roles
    $joueur = $combat['perso1'];
    $ia = $combat['perso2'];

    // Vérifier si l’action est autorisée
    if (!$combat['termine'] && $joueur->is_alive()) {
        //Appliquer l’action
        switch ($action) {
            case 'Attaque':
                $joueur->attaquer($ia);
                $combat['logs'][] = "Tu attaques {$ia->getName()} et infliges {$joueur->getAtk()} de dégâts";
                break;

            case 'Se régénérer':
                $joueur->regenerer(10);
                $combat['logs'][] = "Tu te régénères de 10 pv.";
                break;

            case 'Coup spécial':
                $joueur->coupSpecial($ia);
                $combat['logs'][] = "Tu as utilisé ton coup spécial !";
                break;
        }

        // Vérifier si quelqu’un est mort
        if (!$ia->is_alive() || !$joueur->is_alive()) {
            $combat['termine'] = true;
            $combat['logs'][] = 'Le combat est terminé !';
        }
    }
    //Perso peut riposter ou se soigner
    if (!$combat['termine'] && $ia->is_alive()) {
        $actionIA = actionIA($ia);
        //Appliquer l’action
        switch ($actionIA) {
            case 'Attaque':
                $ia->attaquer($joueur);
                $combat['logs'][] = "{$ia->getName()} t'attaque et t'infliges {$ia->getAtk()} de dégats";
                break;

            case 'Se régénérer':
                $ia->regenerer(20);
                $combat['logs'][] = " {$ia->getName()} s'est soigné de 20 pv.";
                break;

            case 'Coup spécial':
                $ia->coupSpecial($joueur);
                $combat['logs'][] = "{$ia->getName()} a utilisé son coup spécial";
                break;
        }

        // Vérifier si quelqu’un est mort
        if (!$ia->is_alive() || !$joueur->is_alive()) {
            $combat['termine'] = true;
            $combat['logs'][] = 'Le combat est terminé !';
        }
    }

    //Sauvegarde l’état mis à jour dans la session
    $_SESSION['combat'] = $combat;
}

//Le perso 2 est autonome. En fonction de sa vie restante, il décide de l'action qu'il va effectuer
function actionIA($perso)
{
    $seuil = ($perso->getPv() / 100) * 100;
    $rand = rand(1, 100);

    //vie critique
    if ($seuil < 30) {
        if ($rand <= 50) {
            return 'Se régénérer';
        } elseif ($rand <= 80) {
            return 'Attaque';
        } else {
            return 'Coup spécial';
        }
    }

    //vie moyenne
    if ($seuil < 60) {
        if ($rand <= 60) {
            return 'Attaque';
        } elseif ($rand <= 90) {
            return 'Se régénérer';
        } else {
            return 'Coup spécial';
        }
    }

    //vie confortable
    if ($rand <= 90) {
        return 'Attaque';
    }

    return 'Coup spécial';
}

//Affichage dans la vue
if (isset($_POST['action'], $_SESSION['combat'])) {
    jouerTour($_POST['action']);

    header('Location: index.php');
    exit;
}
?>