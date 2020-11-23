else {
        ?>
        <form action = "liste-patients.php" method = "get">
            <input type = "search" name = "terme">
            <input type = "submit" name = "search" value = "Rechercher">
        </form>
        <?php
        $bdd = new PDO('mysql:host=localhost;dbname=hospitalE2N', 'pdo', 'pdo');
        $reponseShows = $bdd->query('SELECT * FROM patients');
        while ($donneesTypes = $reponseShows->fetch()) {
            ?>
            <p>Nom du patient :<?= $donneesTypes['firstname'] . ' ' . $donneesTypes['lastname']; ?></p><p>date de Naissance : <?= $donneesTypes['birthdate']; ?></p><p>mail : <?= $donneesTypes['mail'] . ' Téléphone: ' . $donneesTypes['phone']; ?></p><a href="profil-patient.php?id=<?= $donneesTypes['id']; ?>">accéder au profil</a> <?php ?>
            <a href="liste-patients.php?DELETE=<?= $donneesTypes['id']; ?>"/>Supprimer</a>
    <?php } ?>