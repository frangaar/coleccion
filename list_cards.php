<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>

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
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 row-cols-xxl-6 g-4 margin-top-cards">
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

        <div class="scene">
            <div class="card">
                <div class="card__face card__face--front">
                    <img src="<?php echo $card['imagen'] ?>" class="card-img-top">
                </div>
                <div class="card__face card__face--back">
                    
                    <div class="card-body color-fondo">
                        <?php if(!empty($card['descripcion'])){ ?>
                            <p class="card-text descripcion"><?php echo $card['descripcion'] ?></p>
                        <?php } ?> 
                        <label class="col-12 col-sm-12 col-form-label badges-title">Expansión:</label>
                        <?php if(!empty($selectExpansion)){ ?>
                            <span class="badge text-bg-success mb-2 badges-text"><?php echo $selectExpansion['nombreExpansion'] ?></span>
                        <?php } ?> 
                         
                        <?php if(!empty($selectMana)){ ?>
                        <label class="col-12 col-sm-12 col-form-label badges-title">Tipos de maná:</label><br> 
                        <table class="table table-bordered table-mana">
                            <tbody>
                                <?php foreach ($selectMana as $value) {?>
                                    <tr>
                                        <td class=" <?php echo strtolower($value['nombre']) ?>"><span class="badge text-bg-success badges-text <?php echo strtolower($value['nombre']) ?>"><?php echo $value['nombre'] ?></span></td>
                                        <td class=" <?php echo strtolower($value['nombre']) ?>"><span class="badge text-bg-success badges-mana <?php echo strtolower($value['nombre']) ?>"><?php echo $value['cantidadMana'] ?></span></td>
                                    </tr>
                                <?php } ?>     
                            </tbody>
                        </table>
                        <table class="table table-bordered table-mana view-mana-mobile">
                            <tbody>
                                <tr>
                                <?php foreach ($selectMana as $value) {?>
                                        <td class=" <?php echo strtolower($value['nombre']) ?>"><span class="badge text-bg-success badges-mana <?php echo strtolower($value['nombre']) ?>"><?php echo $value['cantidadMana'] ?></span></td>
                                <?php } ?>     
                                </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                        <label class="col-12 col-sm-12 col-form-label badges-title">Tipo de carta:</label>
                        <span class="badge text-bg-success mb-2 badges-text"><?php echo $tipoCarta['nombre'] ?></span>

                        <?php if($tipoCarta['idTipoCarta'] == 2){ ?>
                            <label class="col-12 col-sm-12 col-form-label badges-title">Ataque/Defensa:</label>
                            <span class="badge text-bg-success badges-ataque"><?php echo $selectExpansion['ataque'] ?> / <?php echo $selectExpansion['defensa'] ?></span>
                        <?php } ?>
                        
                    </div>
                    <div class="card-footer text-end border-footer">
                        <form method="post" action="controller.php">
                            <input type="hidden" name="id" value="<?php echo $card['id'] ?>">
                            <div class="btn-positions">
                                <button type="submit" class="btn btn-outline-primary" name="modificar">
                                    <i class="far fa-edit fa-text"></i>
                                </button>
                                <button type="submit" class="btn btn-outline-danger" name="borrar">
                                    <i class="far fa-trash-alt fa-text"></i>
                                </button>
                            </div>
                        </form>
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

