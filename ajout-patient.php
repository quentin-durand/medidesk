<?php

$bdd = new PDO('mysql:host=localhost;port=3308,dbname=medidesk', 'root', '');

// J'insère les variables
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
        <div class="row m-0 mx-auto">
            <div id="inscriptionCol" class="col-10 col-md-6 mx-auto text-center mt-5">
                <div id="cardAddPatient" class="card p-0 shadow" style="width: 24rem;">
                    <div class="card-body p-0 border-0">
                        <form method="post" action="ajout-patient.php">
                            <h5  id="inscription" class="card-title p-3 text-center">Nouveau Patient</h5>
                            <p class="card-text"> <div class="pr-2 pl-2 mb-1 classTextColor"><p class="m-0"><label for="lastname">Nom</label></p>
                                <input id="lastname" name="lastname" type="text" /></div>
                            <div class="pr-2 pl-2 mb-1 classTextColor">  <p class="m-0"><label for="firstname">Prénom</label></p>
                                <input id="firstname" name="firstname" type="text" /></div>

                            <div class="pr-2 pl-2 mb-1 classTextColor">  <p class="m-0"><label for="birthdate">Date de naissance :</label></p> <input id="birthdate" name="birthdate" type="date" /></div>
                            <div class="pr-2 pl-2 mb-1 classTextColor">  <p class="m-0"><label for="mail">E-mail</label></p>
                                <input id="mail" name="mail" type="mail" /></div>
                            <div class="pr-2 pl-2 mb-1 classTextColor">  <p class="m-0"><label for="phone">Téléphone</label></p>
                                <input id="phone" name="phone" type="phone" /></div>
                            </p>
                            <?php
                            if (COUNT($_POST) > 0) {

                                $lastname = $_POST['lastname']; // Je récupère mes variables
                                $firstname = $_POST['firstname'];
                                $mail = $_POST['mail'];
                                $phone = $_POST['phone'];
                                $birthdate = $_POST['birthdate'];


                                $verif = $bdd->prepare("SELECT mail,phone from patients where mail=:mail OR phone=:phone");
                                $verif->bindValue(':mail', $mail);
                                $verif->bindValue(':phone', $phone);
                                $verif->execute();
                                $verifresult = $verif->fetchAll();
                                if (count($verifresult) > 0) {
                                    ?><div class="pr-2 pl-2 mb-1 classTextColor"> <?php  echo 'ce compte existe déjà'.'<img class="warningImg"  src="img/warningV4.png"/>';?></div><?php
                                } else {
                                    
                                    $request = $bdd->prepare("INSERT INTO patients(firstname,lastname,mail,phone,birthdate) VALUES(:firstname,:lastname, :mail,:phone,:birthdate)");
                                    $request->bindValue(':firstname', $firstname);
                                    $request->bindValue(':lastname', $lastname);
                                    $request->bindValue(':mail', $mail);
                                    $request->bindValue(':phone', $phone);
                                    $request->bindValue(':birthdate', $birthdate);
                                    $request->execute();
                                    var_dump($request->execute());
                                }
                            }

                            ?>
                            <div class="pr-2 pl-2">
                                <input  class="card-link" type="submit" value="valider" />
                            </div>

                        </form>
                    </div><div id="bottomLine"></div>
                </div>
            </div>
            <div class="col-8"></div>
        </div>  

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="script.js"></script>
    </body>
</html>