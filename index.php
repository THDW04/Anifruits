<?php
require_once 'controllers/index.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combat</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="views/liste.php">Liste</a></li>
                <li><a href="index.php" class="active">Arène</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Arène des anifruits</h1>
        <section class="arene">
            <form action="" method="post">
                <?php

                if (isset($_SESSION['combat'])) {

                    echo '<div class="resume">';

                    $logs = $_SESSION['combat']['logs'];
                    $lastTwo = array_slice($logs, -2);

                    foreach ($lastTwo as $log) {
                        echo "<p>$log</p>";
                    }
                }

                echo '</div>';
                ?>
                <?php
                if (!empty($areneObjets)):
                    foreach ($areneObjets as $c):
                        if ($c): ?>
                            <div class="card">
                                <div class="nb"><?= $c->getId() ?></div>
                                <div class="color">
                                    <p class="name"><?= $c->getName() ?></p>
                                    <img src="assets/img/<?= $c->getImg() ?>" alt="">
                                    <div class="infos">
                                        <span><?= $c->getPv() ?> <img src="assets/img/heart.svg" alt=""></span>
                                        <span><?= $c->getAtk() ?> <img src="assets/img/sword.svg" alt=""></span>
                                        <span>x<?= $c->getSpecialCount() ?> <img src="assets/img/star.svg" alt=""></span>
                                    </div>
                                </div>
                                <div class="nb"><?= $c->getId() ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="actions">
                        <input type="submit" value="Attaque" name="action">
                        <input type="submit" value="Se régénérer" name="action">
                        <input type="submit" value="Coup spécial" name="action">
                </form>
                </div>
            <?php else: ?>
                <p id="vide">L'arène est vide, choisissez deux combattants !</p>
                </form>
            <?php endif; ?>
            <br>
            <a href="controllers/reset.php">Vider l'arène</a>
        </section>
        <br>
        <section class="liste" id="liste">
            <table>
                <caption>Liste des anifruits</caption>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>PV</th>
                        <th>ATK</th>
                        <th>Coup Spécial</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anifruits as $a):
                        // Vérification si le perso est déjà dans l'arène
                        $isSelected = in_array($a->getId(), $_SESSION['arene']);
                        ?>
                        <tr class="<?= $isSelected ? 'row-selected' : '' ?>">
                            <td><?= $a->getName() ?></td>
                            <td><?= $a->getPv() ?></td>
                            <td><?= $a->getAtk() ?></td>

                            <td>
                                <small>
                                    <strong><?= $a->getSpecialName() ?></strong><br>
                                    Type: <?= $a->getSpecialType() ?> (x<?= $a->getSpecialMulti() ?>)
                                </small>
                            </td>

                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="id-perso" value="<?= $a->getId() ?>">

                                    <?php if ($isSelected): ?>
                                        <input type="submit" name="remove" value="Dans l’arène" style="background-color: #ff4d4d;">
                                    <?php else: ?>
                                        <input type="submit" name="choose" value="Choisir" <?= count($_SESSION['arene']) >= 2 ? 'disabled' : '' ?>>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>