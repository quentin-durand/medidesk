<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><link href="https://fonts.googleapis.com/css?family=Ropa+Sans:400i&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body  id="backImage">

        <?php require_once 'header.php';
        ?>
          <?php
// fonction suppression de patient

        if (isset($_GET['DELETE'])) {//on vérifie que le paramètre DELETE existe dans l'url
            $bdd = new PDO('mysql:host=localhost;dbname=hospitalE2N', 'pdo', 'pdo'); //connexion à la bdd
            $deleteappointment = $bdd->prepare('DELETE from patients where id=:id'); // on prépare la requête de suppressionen fonction de l'id
            $deleteappointment->bindValue(':id', $_GET['DELETE']); //on bind le paramètre de l'url en tant qu'id pour savoir quel patient supprimer 
            if ($deleteappointment->execute()) {//on vérifie si la requête est executée
                header('Location: liste-patients.php'); // si elle l'est on recharge la page est on maintient l'url propre
            } 
        }


        $bdd = new PDO('mysql:host=localhost;port=3308,dbname=medidesk', 'root', ''); //connexion à la bdd
//$bdd->query("SET NAMES UTF8");
        $page = (!empty($_GET['page']) ? $_GET['page'] : 1); //numéro de page dans l'url
        $limite = 2; //nombre d'éléments limite à afficher

        $debut = ($page - 1) * $limite; //point de reprise d'affichage des éléments
        $patientsNumber=$bdd->query('SELECT count(id) as numberPatient from patients');
   
           if(isset($patientsNumber)and $patientsNumber>0 ) {
        $patientsNumberResult = $patientsNumber->fetchAll();
    
    
        $pageMax=$patientsNumberResult[0]['numberPatient']/$limite;
    }
        ?>
        <div class="p-3">
        <form action = "liste-patients.php" method = "get">
            <input type = "text" name = "terme">
            <input type = "submit" value = "Rechercher">
            <button class="btn addButton btn-lg mt-3 mb-3"><a href="ajout-patient.php">Ajouter un nouveau patient</a></button>
        </form></div> <p>
        <div class="row mx-auto ">
            <div class="col-2 mx-auto">
        <nav aria-label="">
            <ul class="pagination pagination-sm">
                <li class="page-item ">
                </li> <?php if (($page - 2) > 0) { ?>
                    <li class="page-item" ><a class="page-link" href="?page=<?php echo $page - 1; ?>"><?php echo $page - 2; ?></a><?php
        };?></li><?php
        if (($page - 1) > 0) {
            ?>
                    <li class="page-item" ><a class="page-link" href="?page=<?php echo $page - 1; ?>"><?php echo $page - 1; ?></a><?php } ?>
                    <li class="page-item" aria-current="page">
                        <a class="page-link active" href=""><?php echo $page ?></a></li>
                    
                    <?php
                    if(isset($pageMax)){
        if (($page + 1) <=$pageMax){
            ?>
        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>"><?php echo $page + 1; }?></a></li>
                    
                   <?php  if (($page + 2) <=$pageMax){
            ?>
                   <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>"><?php echo $page + 2; }}?></a></li>
            </ul>
        </nav>
            </div>
        </div>





    
    <div class="row mx-auto pt-5">

      <?php


        if (isset($_GET["terme"])) {//vérifie si le paramètre sarch est bien dans l'url et si il est égale à la valeur Rechercher
            $_GET['terme'] = htmlspecialchars($_GET['terme']); //pour sécuriser le formulaire contre les intrusions html
            $terme = $_GET['terme']; //définit la variable terme depuis le paramètre dans l'url
            $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
            $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
            $terme = '%' . $terme . '%';

            if (isset($terme)) {//vérifie si la variable terme existe
                $terme = strtolower($terme);

                $select_terme = $bdd->prepare("SELECT * FROM patients WHERE lastname LIKE :searchLastName OR firstname LIKE :searchFirstName LIMIT :limite OFFSET :debut");

                $select_terme->bindValue(':debut', $debut, PDO::PARAM_INT);
                $select_terme->bindValue(':limite', $limite, PDO::PARAM_INT);
                $select_terme->bindValue(':searchFirstName', $terme);
                $select_terme->bindValue(':searchLastName', $terme);
                $select_terme->execute();
                ?> 

                <?php
                while ($terme_trouve = $select_terme->fetch()) {
                    ?>
                    <div class="col-md-5 col-10 mx-auto">
                        <div class="card bg-light mb-3" style="max-width: 27rem;">
                            <div id="titleCardPatient" class="card-header text-center p-3"><?= $terme_trouve['firstname'] . ' ' . $terme_trouve['lastname']; ?></div>
                            <div class="card-body">
                                <p class="card-title">date de Naissance : <?= $terme_trouve['birthdate']; ?></p> 
                                <p>mail : <?= $terme_trouve['mail']; ?><p><?= ' Téléphone: ' . $terme_trouve['phone']; ?></p>

                            </div> <div class="row m-0">
                                <div  class="col-6 p-2 text-center border-right border-secondary patientButton">
                                    <a href="profil-patient.php?id=<?= $terme_trouve['id'] ?>">accéder au profil</a> </div>
                                <div class="col-6 p-2 text-center patientButton">   <a href="liste-patients.php?DELETE=<?= $terme_trouve['id'] ?>"/>Supprimer</a></div>
                            </div>
                        </div></div>
                <?php } ?>

                <?php
            }
        } else {
            ?>


            <?php
            $bdd = new PDO('mysql:host=localhost;port=3308,dbname=medidesk', 'root', '');
            $reponseShows = $bdd->prepare('SELECT * FROM patients LIMIT :limite OFFSET :debut');
            $reponseShows->bindValue(':debut', $debut, PDO::PARAM_INT);
            $reponseShows->bindValue(':limite', $limite, PDO::PARAM_INT);
            $reponseShows->execute();
            while ($donneesTypes = $reponseShows->fetch()) {
                ?>     <div class="col-md-5 col-10 mx-auto"><div class="card bg-light mb-3" style="max-width: 27rem;">
                        <div id="titleCardPatient" class="card-header p-3 text-center">
                            <?= $donneesTypes['firstname'] . ' ' . $donneesTypes['lastname']; ?></div>
                        <div class="card-body">
                            <p class="card-title">Date de Naissance : <?= $donneesTypes['birthdate']; ?></p>
                            <p>mail : <?= $donneesTypes['mail']; ?></p>

                            <p><?= ' Téléphone: ' . $donneesTypes['phone']; ?></p>  </div>
                            <div class="row m-0">
                                <div  class="col-6 p-2 text-center border-right border-secondary patientButton">
                                 <a href="profil-patient.php?id=<?= $donneesTypes['id']; ?>">accéder au profil</a> <?php ?></div>
                                    <div class="col-6 p-2 text-center patientButton">   <a href="liste-patients.php?DELETE=<?= $donneesTypes['id']; ?>"/>Supprimer</a></div>
                                </div>           
                        </div></div>
                    <?php
                }
            }
            ?></div>

    
     
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="script.js"></script>
</body>
</html>