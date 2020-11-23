<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><link href="https://fonts.googleapis.com/css?family=Ropa+Sans:400i&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body  id="backImage">

        <?php require_once 'header.php';
        ?>
<div class="row m-0 mx-auto">
        <?php
        $bdd = new PDO('mysql:host=localhost;port=3308,dbname=medidesk', 'root', '');
        if(isset($_POST['patient'])){
            $patient = $_POST['patient'];
        }
        if(isset($_POST['dateAppointment'])&isset($_POST['hour'])){
        $hour = $_POST['dateAppointment'] . ' ' . $_POST['hour'];
        }
//var_dump($hour);
//var_dump($patient);

        $select = $bdd->prepare("SELECT * from patients");
        $select->execute();
        ?>
        <div id="inscriptionCol" class="col-10 col-md-6   mx-auto">
            <div id="cardAddPatient" class="card p-0 shadow" style="width: 24rem;">
                <div class="card-body p-0 border-0">
                    <h5  id="inscription" class="card-title p-3 text-center">Nouveau Rendez-vous</h5>
                    <form method="post" action="ajout-rendezvous.php">
                        <p class="card-text"><div class="pr-2 pl-2 mb-1 classTextColor"><p class="m-0">    <label> Nom du patient</label>
                                <select name="patient"><?php
                                    while ($donneesTypes = $select->fetch()) {
                                        ?>
                                        <option value="<?= $donneesTypes['id']; ?>"><?= $donneesTypes['firstname'] . ' ' . $donneesTypes['lastname']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select></p></div>
                        <div class="pr-2 pl-2 mb-1 classTextColor">  <p class="m-0"> <label>Date</label></p>   <input type="date" name="dateAppointment"/></div>
                        <div class="pr-2 pl-2 mb-1 classTextColor">  <p class="m-0"> <label>Heure du rendez-vous</label> <select name="hour">
                                    <?php
                                    for ($startHour = 9; $startHour <= 19; $startHour++) {
                                        if ($startHour < 10) {
                                            echo'<option>0' . $startHour . ':00:00</option>';
                                        } else {
                                            echo'<option>' . $startHour . ':00:00</option>';
                                        }
                                    }
                                    ?>
                                </select></p>
                            <?php
                            if (COUNT($_POST) > 0) {

                                $verif = $bdd->prepare("SELECT dateHour from appointments where dateHour=:dateHour");
                                $verif->bindValue(':dateHour', $hour);
                                $verif->execute();
                                $verifresult = $verif->fetchAll();
                                if (count($verifresult) > 0) {
                                    echo 'cet horaire est indisponible'.'<img class="warningImg"  src="img/warningV4.png"/>';
                                } else {
                                    $addAppointment = $bdd->prepare("INSERT INTO appointments(dateHour,idPatients) VALUES(:dateHour,:idPatients)");
                                    $addAppointment->bindValue(':dateHour', $hour);
                                    $addAppointment->bindValue(':idPatients', $patient);
                                    if ($addAppointment->execute()) {
                                        echo 'yes';
                                    } else {
//        var_dump($addAppointement);
                                    }
                                }
                            }
                            ?>
                        </div><div class="pr-2 pl-2">
                            <input type="submit" value="valider"/>
                        </div>

                    </form>

                </div><div id="bottomLine"></div>
            </div>
        </div>
        <div class="col-8"></div>
    </div>     </div></div></div></div>







<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>
</html>

