<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>
<body>

<?php 
    session_start();
    include_once('menu.php');
    include_once('db.php');

    if(isset($_SESSION['success']) == 1){ ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
        unset($_SESSION['success']);
    }else if(isset($_SESSION['error']) == 1){ ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?php echo $_SESSION['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
        unset($_SESSION['error']);
    }

    $conn = openDB();

    $selectAllSql = "select * from cartas";
    $selectAll = $conn->prepare($selectAllSql);
    $selectAll->execute();
    
    $cardsList = $selectAll->fetchAll();

    if(empty($cardsList)){ ?>
        <div class="alert alert-warning" role="alert">
            No hay datos para mostrar
        </div>
    <?php
        }
    ?>

    <div class="container-fluid mt-3">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-4 margin-top-cards">
        <?php
        foreach ($cardsList as $card) {

            $selectExpansionSql = "select c.nombre,c.descripcion,e.nombreExpansion,c.ataque,c.defensa from cartas c, expansiones e where c.idExpansion=e.id and c.id=".$card['id'];
            $selectExpansion = $conn->prepare($selectExpansionSql);
            $selectExpansion->execute();
            
            $selectExpansion = $selectExpansion->fetch();

            $selectManaSql = "SELECT tm.nombre,ct.cantidadMana FROM cartas c, cartas_tipomana ct, tipo_mana tm where c.id=ct.idCarta and ct.idTipoMana=tm.id and c.id=".$card['id'];            
            $selectMana = $conn->prepare($selectManaSql);
            $selectMana->execute();

            $selectMana = $selectMana->fetchAll();

            $listaTipoManaSql = "SELECT tm.nombre FROM cartas c, cartas_tipomana ct, tipo_mana tm where c.id=ct.idCarta and ct.idTipoMana=tm.id and c.id=".$card['id'];            
            $listaTipoMana = $conn->prepare($listaTipoManaSql);
            $listaTipoMana->execute();

            $listaTipoMana = $listaTipoMana->fetchAll();

            $tipoCartaSql = "select c.idTipoCarta,tc.nombre from cartas c,tipo_carta tc where c.idTipoCarta=tc.id and c.id=".$card['id'];
            $tipoCarta = $conn->prepare($tipoCartaSql);
            $tipoCarta->execute();

            $tipoCarta = $tipoCarta->fetch();
            
        ?>
            
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <div class="col">
                            <div class="card h-100 color-fondo">
                                
                                <img src="img/1.jpg" class="card-img-top">
                                
                            </div>
                        </div>
                    </div>
                    <div class="flip-card-back">
                        <div class="col">
                            <div class="card h-100 color-fondo">
                                
                                <div class="card-body color-fondo">
                                    
                                    <p class="card-text descripcion"><?php echo $card['descripcion'] ?></p>
                                    <label class="col-sm-6 col-form-label badges-title">Expansión:</label><br>
                                    <?php if(!empty($selectExpansion)){ ?>
                                        <span class="badge text-bg-success mb-2"><?php echo $selectExpansion['nombreExpansion'] ?></span>
                                    <?php } ?> 
                                    <br> 
                                    <?php if(!empty($selectMana)){ ?>
                                    <label class="col-sm-6 col-form-label badges-title">Tipos de maná:</label><br> 
                                    <table class="table table-sm table-bordered table-mana">
                                        <tbody>
                                            <?php foreach ($selectMana as $value) {?>
                                                <tr>    
                                                    <td><span class="badge text-bg-success mb-2 <?php echo strtolower($value['nombre']) ?>"><?php echo $value['nombre'] ?></span></td>
                                                    <td><span class="badge text-bg-success mb-2 <?php echo strtolower($value['nombre']) ?>"><?php echo $value['cantidadMana'] ?></span></td>
                                                </tr>
                                            <?php    
                                                }
                                            } ?> 
                                        </tbody>
                                    </table>
                                    
                                    <label class="col-sm-6 col-form-label badges-title">Tipo de carta:</label><br>
                                    <span class="badge text-bg-success mb-2"><?php echo $tipoCarta['nombre'] ?></span><br>

                                    <?php if($tipoCarta['idTipoCarta'] == 2){ ?>
                                        <label class="col col-form-label badges-title">Ataque/Defensa:</label><br> 
                                        <span class="badge text-bg-success badges-ataque"><?php echo $selectExpansion['ataque'] ?> / <?php echo $selectExpansion['defensa'] ?></span>
                                    <?php } ?>
                                    
                                </div>
                                <div class="card-footer text-end">
                                    <form method="post" action="controller.php">
                                        <input type="hidden" name="id" value="<?php echo $card['id'] ?>">
                                        <div class="btn-positions">
                                            <button type="submit" class="btn btn-outline-primary" name="modificar">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button type="submit" class="btn btn-outline-danger" name="borrar">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

    <?php
        }
    ?>
        </div>
    </div>

</body>
</html>

<!-- <script>

var card = document.querySelector('.card');
card.addEventListener( 'click', function() {
  card.classList.toggle('is-flipped');
});
</script> -->