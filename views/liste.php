<?php
require_once '../controllers/index.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des anifruits</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="liste.php" class="active">Liste</a></li>
                <li><a href="../index.php">Arène</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Gestion des anifruits</h1>
        <button id="ajoutBtn" class="action new">Ajouter un anifruit</button>
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
                    <?php
                    foreach ($anifruits as $a): ?>
                        <tr>
                            <td><?= $a->getName() ?></td>
                            <td><?= $a->getPv() ?></td>
                            <td><?= $a->getAtk() ?></td>
                            <td>
                                <?= $a->getSpecialName() ?><br>
                                <small>(Type: <?= $a->getSpecialType() ?> | Puissance:
                                    x<?= $a->getSpecialMulti() ?>)</small>
                            </td>
                            <td>
                                <div class="tab-action">
                                    <a href='update.php?id=<?= $a->getId() ?>' class='edit action'>Modifier</a>

                                    <form action="" method="post">
                                        <input type="hidden" name="id" value="<?= $a->getId() ?>">
                                        <input type="submit" value="Supprimer" name="delete">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        
        <section class="modal">
            <div>
                <button id="close">Fermer</button>
                <div class="ajout form">
                    <h2>Ajouter un anifruit</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-grid">
                            <div class="form-section">
                                <h3>Infos de base</h3>
                                <div>
                                    <label for="name">Nom de l'anifruit :</label>
                                    <input type="text" name="name" id="name" placeholder="Ex: Citron Ninja" required>
                                </div>
                                <div>
                                    <label for="pv">Points de vie (50-200) :</label>
                                    <input type="number" name="pv" id="pv" min="50" max="200" value="100" required>
                                </div>
                                <div>
                                    <label for="atk">Points d'attaque :</label>
                                    <input type="number" name="atk" id="atk" min="1" max="50" required>
                                </div>
                                <div>
                                    <label for="img">Image (Format carré) :</label>
                                    <input type="file" name="img" id="img" accept="image/*" required>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3>Capacité Spéciale</h3>
                                <div>
                                    <label for="specialName">Nom du coup spécial :</label>
                                    <input type="text" name="specialName" id="specialName"
                                        placeholder="Ex: Pressage fatal" required>
                                </div>
                                <div>
                                    <label for="specialType">Type d'effet :</label>
                                    <select name="specialType" id="specialType" required>
                                        <option value="attaque">Attaque (Dégâts x Multiplicateur)</option>
                                        <option value="soin">Soin (Soin de base x Multiplicateur)</option>
                                        <option value="défense">Défense (Dégâts reçus / Multiplicateur)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="specialMulti">Puissance (Multiplicateur) :</label>
                                    <input type="number" name="specialMulti" id="specialMulti" min="0" max="5"
                                        step="0.1" placeholder="Ex: 1.5" required>
                                    <small>Coefficient appliqué à l'effet spécial.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <input type="submit" name="add" value="Créer l'anifruit" class="btn-submit">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script>
        let btn = document.querySelector('#ajoutBtn');

        btn.addEventListener("click", () => {
            document.querySelector(".modal").style.display = "block";
        });

        document.querySelector("#close").addEventListener("click", () => {
            document.querySelector(".modal").style.display = "none";
        });
    </script>
</body>

</html>