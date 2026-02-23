<?php require_once '../controllers/index.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un anifruit</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <section>
            <h1>Modification de <?= $anifruit->getName() ?></h1>
            <a href="liste.php" id="retour">Retour à la liste</a>
            <div class="form update">
                <form action="" method="POST">
                    <div class="form-grid">
                        <div>
                            <h3>Infos de base</h3>
                            <div>
                                <label for="name">Nom de l'anifruit :</label><br>
                                <input type="text" name="name" id="name" value="<?= $anifruit->getName() ?>" required>
                            </div>
                            <div>
                                <label for="pv">Nombre de point de vie :</label><br>
                                <input type="number" name="pv" id="pv" min="50" max="200" value="<?= $anifruit->getPv() ?>"
                                    required>
                            </div>
                            <div>
                                <label for="atk">Nombre de point d'attaque :</label><br>
                                <input type="number" name="atk" id="atk" min="1" max="50" value="<?= $anifruit->getAtk() ?>" required>
                            </div>
                        </div>
                        <div>
                            <div>
                                <h3>Capacité Spéciale</h3>
                                <label for="specialName">Nom du coup spécial :</label><br>
                                <input type="text" name="specialName" id="specialName"
                                    value="<?= $anifruit->getSpecialName() ?>" required>
                            </div>
                            <div>
                                <label for="specialType">Type du coup :</label><br>
                                <select name="specialType" id="specialType" required>
                                    <option value="attaque" <?= $anifruit->getSpecialType() == 'attaque' ? 'selected' : '' ?>>Attaque
                                    </option>
                                    <option value="soin" <?= $anifruit->getSpecialType() == 'soin' ? 'selected' : '' ?>>Soin</option>
                                    <option value="défense" <?= $anifruit->getSpecialType() == 'défense' ? 'selected' : '' ?>>Défense
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="specialMulti">Puissance du coup :</label><br>
                                <input type="number" name="specialMulti" id="specialMulti" min="0" max="5"
                                    value="<?= $anifruit->getSpecialMulti() ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="submit" name="update" value="Modifier">
                </form>
            </div>
        </section>
    </main>
</body>

</html>