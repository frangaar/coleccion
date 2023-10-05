<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
<?php
session_start();
require_once('menu.php');
require_once('funciones.php');
include_once('db.php');

$conn=openDB();

    if(isset($_POST['guardar'])){

        $guardado=crearCarta($conn);
        
        if($guardado){
            $_SESSION['success'] = "Carta añadida correctamente";
        } else{
            $_SESSION['error'] = "Error al añadir carta";
        }        
    }

    if(isset($_POST['modificar'])){

        modificarCarta($conn);
    }


    if(isset($_POST['borrar'])){

        $borrar=borrarCarta($conn);
        
        if($borrar){
            $_SESSION['success'] = "Carta borrada correctamente";
        }else{
            $_SESSION['error'] = "Error al borrar carta";
        }

    }

    
    if(isset($_POST['actualizar'])){

        $actualizar=actualizarCarta($conn);

        if($actualizar){
            $_SESSION['success'] = "Carta actualizada correctamente";
        }else{
            $_SESSION['error'] = "Error al actualizar carta";
        }

    }

    $conn = closeDB();


    if(isset($_SESSION['success']) == 1){
        header("Location: list_cards.php");
    }

?>

</body>
</html>