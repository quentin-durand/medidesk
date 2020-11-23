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
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

$hour = $_POST['dateAppointment'] . ' ' . $_POST['hour'];

if (COUNT($_POST) > 0) {
    $modifAppointment = $bdd->prepare("UPDATE appointments SET dateHour=:dateHour where id=:id");
    $modifAppointment->bindValue(':dateHour', $hour);
    $modifAppointment->bindValue(':id', $_GET['id']);
    if ($modifAppointment->execute()) {
        echo 'yes';
    } 
}

$appointmentsList = $bdd->prepare('SELECT patients.lastname,patients.firstname,appointments.dateHour,patients.id FROM patients LEFT JOIN appointments ON patients.id=appointments.idPatients where appointments.id=:id');
$appointmentsList->bindValue(':id', $_GET['id']);
$appointmentsList->execute();

while ($donneesTypes = $appointmentsList->fetch()) {
    ?><div class="row m-0 mx-auto">
            <div id="inscriptionCol" class="col-10 col-md-4 mx-auto mt-5 row">
                <div id="cardAddPatient" class="card p-0 shadow col-10" style="width: 18rem;">
                    <div class="card-body p-0 border-0">
  
                        <h5 id="inscription" class="card-title p-3 text-center"><?= $donneesTypes['firstname'] . ' ' . $donneesTypes['lastname']; ?></h5>
                        <div class="p-2">
    <form method="post" action="rendezvous.php?id=<?= $donneesTypes['id']?>">
       
        <p><span class="classTextColor">Date du rendez-vous :</span> <?= (strftime('%d %B %Y', strtotime($donneesTypes['dateHour']))); ?></p>
        <p><span class="classTextColor">Heure du rendez-vous : </span><?= (strftime('%H h %M', strtotime($donneesTypes['dateHour']))); ?></p><p>Modifier votre Rendez-vous</p>
        <p></p>
        
        <input type="date" name="dateAppointment" value="<?= (strftime('%Y-%m-%d', strtotime($donneesTypes['dateHour']))); ?>"/>   
    <label>Changer l'heure du rendez-vous</label> <select name="hour" >
        <?php
        for ($startHour = 9; $startHour <= 19; $startHour++) {
            if ($startHour < 10) {
                echo'<option>0' . $startHour . ':00:00</option>';
            } else {
                echo'<option>' . $startHour . ':00:00</option>';
            }
        }
        ?>
    </select>
    <input type="submit" value="valider"/>
    </form></div>
                    </div></div></div></div>
    <?php } ?>

    
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 <script src="script.js"></script>
    </body>
</html>