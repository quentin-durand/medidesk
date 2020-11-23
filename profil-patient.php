<?php
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
?>

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
$bdd = new PDO('mysql:host=localhost;dbname=hospitalE2N', 'pdo', 'pdo');
$lastname = $_POST['lastname']; // Je récupère mes variables
$firstname = $_POST['firstname'];
$mail = $_POST['mail'];
$phone = $_POST['phone'];
$birthdate = $_POST['birthdate'];


if (COUNT($_POST) > 0) {
    $modif = $bdd->prepare("UPDATE patients set firstname=:firstname, lastname=:lastname,mail=:mail,phone=:phone,birthdate=:birthdate where id=:id");

    $modif->bindValue(':firstname', $firstname);
    $modif->bindValue(':lastname', $lastname);
    $modif->bindValue(':mail', $mail);
    $modif->bindValue(':phone', $phone);
    $modif->bindValue(':birthdate', $birthdate);
    $modif->bindValue(':id', $_GET['id']);

    $modif->execute();
    
}
//echo $_GET['id'];
$requestProfil = $bdd->prepare('SELECT * FROM patients where id=:id');

$requestProfil->bindValue(':id', $_GET['id']);
$requestProfil->execute();

while ($donneesTypes = $requestProfil->fetch()) {
    ?><div class="row m-0 mx-auto">
            <div id="inscriptionCol" class="col-10 col-md-4 mx-auto mt-5">
                <div id="cardAddPatient" class="card p-0 shadow" style="width: 18rem;">
                    <div class="card-body p-0 border-0">
    <form method="POST" action="profil-patient.php?id=<?= $donneesTypes['id'] ?>">
        <div>
            <h5  id="inscription" class="card-title p-3 text-center">Fiche du patient</h5></div>
            <div class="p-2">    <input type="text" name="firstname" value="<?= $donneesTypes['firstname']; ?>"/>  
            <input type="text" name="lastname"  value="<?= $donneesTypes['lastname']; ?>"/>  
        </p><p>date de Naissance : <input type="date" name="birthdate" value="<?= $donneesTypes['birthdate']; ?>"/>  

        </p><p>mail :  <input name="mail" type="email"   value="<?= $donneesTypes['mail']; ?>"/> 
            Téléphone: <input name="phone" type="tel" value="<?= $donneesTypes['phone']; ?>"/>
        </p>
        <input type="button"  onclick="undisable()" value="modifier"/>
        <input type="submit" value="enregistrer"/>

    </form>
</div>             
                    </div><div id="bottomLine"></div>
                </div>
            </div>
     
    <?php
}
?>
<div class="card col-5 mt-5 mx-auto">
    <div class="card-body">
      <h5 class="card-title">Liste des Rendez-vous</h5>
      <p class="card-text"><?php
$appointmentsProfil = $bdd->prepare('SELECT appointments.id AS appointmentID,appointments.dateHour, patients.lastname,patients.firstname,patients.id AS patientsID FROM appointments LEFT JOIN patients ON patients.id=appointments.idPatients where patients.id=:id');

$appointmentsProfil->bindValue(':id', $_GET['id']);
$appointmentsProfil->execute();

while ($appointementsInfos = $appointmentsProfil->fetch()) {
    ?>

      <li>  <span class="appointementList font-weight-bold">Date et heure du rendez-vous</span> : Le <?= (strftime('%d %B %Y', strtotime($donneesTypes['dateHour']))); ?> à
        <?= (strftime('%H h %M', strtotime($donneesTypes['dateHour']))); ?>
   <?php }?></li>
    </p>
    
    </div>
</div></div>


   
<script src="script.js"></script>







<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>
</html>
