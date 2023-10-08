<?php


function openDB(){

    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $conn = null;
    
    try {
      $conn = new PDO("mysql:host=$servername;dbname=magic", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }

    return $conn;
}

function closeDB(){

    return null;
}

function crearCarta($conn){

  $guardado = false;

  $nombre=$_POST['nombreCarta'];
  $descripcion=$_POST['descCarta'];
  $idExpansion=$_POST['idExpansion'];
  
  if($_POST['ataque'] == "" || !is_numeric($_POST['ataque'])){
    $ataque=0;
  }else{
    $ataque=$_POST['ataque'];    
  } 

  if($_POST['defensa'] == "" || !is_numeric($_POST['defensa'])){
    $defensa=0;
  }else{
    $defensa=$_POST['defensa'];
  }

  $tipoCarta=$_POST['idTipoCarta'];
  
  $imagen=uploadImages();

  $insertCardSql = "INSERT INTO cartas VALUES (:id,:nombre,:descripcion,:idExpansion,:ataque,:defensa,:idTipoCarta,:imagen)";
  
  $insertCard = $conn->prepare($insertCardSql);
  $null = null;
  $insertCard->bindParam(':id',$null);
  $insertCard->bindParam(':nombre',$nombre);
  $insertCard->bindParam(':descripcion',$descripcion);
  $insertCard->bindParam(':idExpansion',$idExpansion);
  $insertCard->bindParam(':ataque',$ataque);
  $insertCard->bindParam(':defensa',$defensa);
  $insertCard->bindParam(':idTipoCarta',$tipoCarta);
  $insertCard->bindParam(':imagen',$imagen);
  $insertCard->execute();

  // Obtener id nueva carta creada
  $selectSql = "select MAX(id) as id from cartas";
  $selectAll = $conn->prepare($selectSql);
  $selectAll->execute();

  $idCarta = $selectAll->fetch();

  $manaList=array();

  if(isset($_POST['mana'])){

    $primary=$_POST['mana'];
    $secondary=$_POST['values'];

    foreach ($secondary as $keySecondary => $value) {
        
        if(array_key_exists($keySecondary, $primary)){
          if(!empty($value) && is_numeric($value)){
            $manaList += [$keySecondary => $value];
          }
          
        }    
    }


    $insertManaTypesSql = "INSERT INTO cartas_tipomana VALUES (:idCarta,:idTipoMana,:cantidadMana)";
    
    $insertManaTypes = $conn->prepare($insertManaTypesSql);

    $data = [];

    foreach ($manaList as $key => $value) {
      $data = ['idCarta'=>$idCarta['id'],'idTipoMana'=>$key,'cantidadMana'=>$value];
      $insertManaTypes->execute($data);
    }
  }

    

  $guardado = true;

  return $guardado;
}

function borrarCarta($conn){

  $borrado = false;

  $id=$_POST['id'];
  $deleteSql = "delete from cartas where id=".$id;
  $delete = $conn->prepare($deleteSql);
  $delete->execute();

  $borrado = true;

  return $borrado; 

}

function modificarCarta($conn){

  // Obtener datos para rellenar formulario de modificación
  $id=$_POST['id'];
  $selectSql = "select * from cartas where id=".$id;
  $selectAll = $conn->prepare($selectSql);
  $selectAll->execute();

  $cardData = $selectAll->fetch();

  include('formModif.php');
}

function actualizarCarta($conn){

  $actualizar=false;

  // Actualizar datos carta
  $id=$_POST['id'];
  $nombre=$_POST['nombreCarta'];
  $descripcion=$_POST['descCarta'];
  $expansion=$_POST['idExpansion'];

  if($_POST['ataque'] == "" || !is_numeric($_POST['ataque'])){
    $ataque=0;
  }else{
    $ataque=$_POST['ataque'];    
  } 

  if($_POST['defensa'] == "" || !is_numeric($_POST['defensa'])){
    $defensa=0;
  }else{
    $defensa=$_POST['defensa'];
  }

  $tipoCarta=$_POST['idTipoCarta'];

  if(!empty($_FILES['imgCarta']['name'])){
      $imagen=uploadImages();
  }


  $updateCardSql = "UPDATE cartas set nombre='".$nombre."'";

  //if(!empty($descripcion)){
      $updateCardSql = $updateCardSql.",descripcion='".$descripcion."'";
  //}

  $updateCardSql = $updateCardSql.",idExpansion=".$expansion;
  $updateCardSql = $updateCardSql.",ataque=".$ataque;
  $updateCardSql = $updateCardSql.",defensa=".$defensa;
  $updateCardSql = $updateCardSql.",idTipoCarta=".$tipoCarta;

  if(isset($imagen)){
      $updateCardSql = $updateCardSql.",imagen='".$imagen."'";
  }
                                    
  $updateCardSql = $updateCardSql." where id=".$id;

  $updateCard = $conn->prepare($updateCardSql);
  $updateCard->execute();

  $updateCard->fetchAll();


  // Actualizar tabla de relaciones carta y expansion
  $updateExpansionCardSql = "UPDATE cartas set idExpansion=".$expansion;
  $updateExpansionCardSql = $updateExpansionCardSql." where id=".$id;

  $updateExpansionCard = $conn->prepare($updateExpansionCardSql);
  $updateExpansionCard->execute();

  $actualizar=true;


  $deleteExpansionCardSql = "delete from cartas_tipomana where idCarta=".$id;

  $deleteExpansionCard = $conn->prepare($deleteExpansionCardSql);
  $deleteExpansionCard->execute();


  $manaList=array();

  if(isset($_POST['mana'])){

    $primary=$_POST['mana'];
    $secondary=$_POST['values'];

    foreach ($secondary as $keySecondary => $value) {
        
        if(array_key_exists($keySecondary, $primary)){
          if(!$value == "" && is_numeric($value)){
            $manaList += [$keySecondary => $value];
          }
        }    
    }
  }


  $insertManaTypesSql = "INSERT INTO cartas_tipomana VALUES (:idCarta,:idTipoMana,:cantidadMana)";
  
  $insertManaTypes = $conn->prepare($insertManaTypesSql);

  $data = [];

  foreach ($manaList as $key => $value) {
    $data = ['idCarta'=>$id,'idTipoMana'=>$key,'cantidadMana'=>$value];
    $insertManaTypes->execute($data);
  }  

  return $actualizar;
}

?>