<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Ropa+Sans:400i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>

<body id="backImage">

    <?php require_once 'header.php';
    ?>

    <?php
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    if (isset($_GET['DELETE'])) {
        $bdd = new PDO('mysql:host=localhost;port=3308,dbname=medidesk', 'root', '');
        $deleteappointment = $bdd->prepare('DELETE from appointments where id=:id');
        $deleteappointment->bindValue(':id', $_GET['DELETE']);
        if ($deleteappointment->execute()) {
            header('Location: liste-rendezvous.php');
        }
    }


    $bdd = new PDO('mysql:host=localhost;port=3308,dbname=medidesk', 'root', ''); //connexion à la bdd
    //$bdd->query("SET NAMES UTF8");
    $page = (!empty($_GET['page']) ? $_GET['page'] : 1); //numéro de page dans l'url
    $limite = 2; //nombre d'éléments limite à afficher

    $debut = ($page - 1) * $limite; //point de reprise d'affichage des éléments
    $patientsNumber = $bdd->query('SELECT count(id) as numberPatient from patients');
    if (isset($patientsNumber) and $patientsNumber > 0) {
        $patientsNumberResult = $patientsNumber->fetchAll();
    }

    if (isset($patientsNumberResult)) {
        $pageMax = $patientsNumberResult[0]['numberPatient'] / $limite;
    }

    ?>



    <div class="col-md-4 col-10 text-center"><button type="button" class="btn addButton btn-lg mt-3 mb-3">
            <a href="ajout-rendezvous.php">Prendre un rendez-vous</a></button></div>


    <div class="row mx-auto ">
        <div class="col-2 mx-auto">
            <nav aria-label="">
                <ul class="pagination pagination-sm">
                    <li class="page-item ">
                    </li> <?php if (($page - 2) > 0) { ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>"><?php echo $page - 2; ?></a><?php };
                                                                                                                                        ?></li><?php
                if (($page - 1) > 0) {
                ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>"><?php echo $page - 1; ?></a><?php } ?>
                            <li class="page-item" aria-current="page">
                                <a class="page-link active" href=""><?php echo $page ?></a></li>

                            <?php
                            if(isset($pageMax)){
                            if (($page + 1) <= $pageMax) {
                            ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>"><?php echo $page + 1;
                                                                                                                } ?></a></li>

                                <?php if (($page + 2) <= $pageMax) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>"><?php echo $page + 2;
                                                                                                                    }} ?></a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row mx-auto pt-5">
        <?php
        $bdd = new PDO('mysql:host=localhost;port=3308,dbname=hospitalE2N', 'root', '');


        $appointmentsList = $bdd->prepare('SELECT appointments.id AS appointmentID,appointments.dateHour, patients.lastname,patients.firstname,patients.id FROM appointments LEFT JOIN patients ON patients.id=appointments.idPatients LIMIT :limite OFFSET :debut');
        $appointmentsList->bindValue(':debut', $debut, PDO::PARAM_INT);
        $appointmentsList->bindValue(':limite', $limite, PDO::PARAM_INT);
        $appointmentsList->execute();



        while ($donneesTypes = $appointmentsList->fetch()) {
        ?>
            <div class="col-md-5 col-10 mx-auto">
                <div class="card bg-light mb-3" style="max-width: 27rem;">
                    <div id="titleCardPatient" class="card-header p-3 text-center"> Rendez-vous</div>
                    <div class="card-body">
                        <p class="card-title">
                            <p>date du rendez-vous : <?= (strftime('%d %B %Y %H h %M', strtotime($donneesTypes['dateHour']))); ?></p>
                            <p>Nom du patient :<?= $donneesTypes['firstname'] . ' ' . $donneesTypes['lastname']; ?></p>
                    </div>
                    <div class="row m-0">
                        <div class="col-6 p-2 text-center patientButton">
                            <a href="rendezvous.php?id=<?= $donneesTypes['appointmentID'] ?>">voir le rendez-vous</a><?php ?></div>
                        <div class="col-6 p-2 text-center patientButton">
                            <a href="liste-rendezvous.php?DELETE=<?= $donneesTypes['appointmentID'] ?>">supprimer</a></div>
                    </div>
                </div>
            </div>
        <?php } ?></div>

    <!--    <a href="https://fr.freepik.com/photos-vecteurs-libre/fond">Fond photo créé par fanjianhua - fr.freepik.com</a><a href="https://fr.freepik.com/photos-vecteurs-libre/fond">Fond photo créé par xb100 - fr.freepik.com</a>-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>