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
<!-- <script>

    document.addEventListener("DOMContentLoaded", (event) => {
        let imagen = document.getElementById('formFile'); 
    
        imagen.addEventListener('change', function(e) {
            if (e.target.files[0]) {

            let thumb = document.getElementById('img-preview'); 

            console.log(imagen.value);
            const splitedWord = imagen.value.split('\\');
            thumb.src = 'img/'+splitedWord[2];
            
            }
        });
    });

</script> -->
<body>
<?php include_once('menu.php'); ?>
<?php include_once('db.php') ?>

<?php

    $conn=openDB();

    $espansionListSql = "select id,nombreExpansion from expansiones";
    $expansionList = $conn->prepare($espansionListSql);
    $expansionList->execute();

    $expansionList = $expansionList->fetchAll();

    $espansionSelectedSql = "select idExpansion from cartas where id=".$cardData['id'];
    $espansionSelected = $conn->prepare($espansionSelectedSql);
    $espansionSelected->execute();

    $espansionSelected = $espansionSelected->fetch();

    $listaTipoManaSql = "select * from tipo_mana order by id asc";            
    $listaTipoMana = $conn->prepare($listaTipoManaSql);
    $listaTipoMana->execute();

    $listaTipoManaInfo = $listaTipoMana->fetchAll();

    $listaTipoManaDatosSql = "select idTipoMana,cantidadMana from cartas_tipomana where idCarta=".$cardData['id'];
    $listaTipoManaDatos = $conn->prepare($listaTipoManaDatosSql);
    $listaTipoManaDatos->execute();

    $listaTipoManaDatosRellenar = $listaTipoManaDatos->fetchAll();

    
    $tiposCartaListSql = "select id,nombre from tipo_carta order by id";
    $tiposCartaList = $conn->prepare($tiposCartaListSql);
    $tiposCartaList->execute();

    $tiposCartaList = $tiposCartaList->fetchAll();

    $tipoCartaSql = "select c.idTipoCarta,tc.nombre,c.ataque,c.defensa,c.imagen from cartas c,tipo_carta tc where c.idTipoCarta=tc.id and c.id=".$cardData['id'];
    $tipoCarta = $conn->prepare($tipoCartaSql);
    $tipoCarta->execute();

    $tipoCarta = $tipoCarta->fetch();


    $conn=closeDB();
?>

<div class="container mt-3">
    <div class="card margin-top-mobile-cards">
        <div class="card-body">
            <h5 class="card-title">Formulario de modificación de la carta</h5>
            <form action="controller.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <input type="hidden" name="id" value="<?php echo $cardData['id'] ?>">
                    <label for="nombreCarta" class="col-sm-3 col-form-label">Nombre de la carta:</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" name="nombreCarta" id="nombreCarta" value="<?php echo $cardData['nombre'] ?>" aria-label="input example">
                    </div>
                </div>
                <div class="mb-3 float-left">
                    <label for="formFile" class="form-label">Imagen</label>
                    <?php   
                            $required = "";
                            if($tipoCarta['imagen'] == ""){ 
                                $required = "required";
                            }
                    ?>
                    <input class="form-control" type="file" id="formFile" name="imgCarta" <?php echo $required ?>>
                </div>
                <div class="mb-3">
                    <img src="<?php echo $tipoCarta['imagen'] ?>" class="img-thumbnail" id="img-preview">
                </div>
                <div class="mb-3 row">
                    <label for="descCarta" class="col-sm-3 col-form-label">Descripción de la carta:</label>
                    <div class="sm-8">
                        <textarea class="form-control" name="descCarta" id="descCarta" value="" rows="3"><?php echo $cardData['descripcion'] ?></textarea>
                    </div>
                </div>
                

                <div class="mb-3 row">
                    <label for="descCarta" class="col-sm-3 col-form-label">Expansion:</label>
                    <div class="sm-8">
                        <select class="form-select" name="idExpansion" aria-label="select">
                        <option value="0" selected>Escoge una coleccion...</option>
                        <?php 
                            foreach ($expansionList as $value) {
                                $selected = "";
                                if($espansionSelected['idExpansion'] == $value['id']){
                                    $selected = "selected";
                                }
                        ?>
                            <option value="<?php echo $value['id'] ?>" <?php echo $selected ?>><?php echo $value['nombreExpansion'] ?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-12 col-form-label lbl-tipo-mana">Tipos de maná:</label>
                    <?php 
                        
                    foreach ($listaTipoManaInfo as $tipo){ 
                        $checked = "";
                        $value = "";
                        $cantidad = "";

                        foreach ($listaTipoManaDatosRellenar as $mana) {
                            if($tipo['id'] == $mana['idTipoMana']){
                                $checked = "checked";
                                $value = $mana['idTipoMana'];
                                $cantidad = $mana['cantidadMana'];
                            }
                        }                     
                    ?>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="flexCheckDefault_<?php echo $tipo['id'] ?>"><?php echo $tipo['nombre'] ?></label>
                        <input class="form-control form-control-sm num-mana" type="text" name="values[<?php echo $tipo['id'] ?>]" value="<?php echo $cantidad ?>" aria-label="input example">
                        <input class="form-check-input" name="mana[<?php echo $tipo['id'] ?>]" type="checkbox" <?php echo $checked ?> value="<?php echo $tipo['id'] ?>" id="flexCheckDefault_<?php echo $tipo['id'] ?>">
                    </div>
                    <?php 
                    } 
                    ?>
                </div>

                <div class="mb-3 row">
                    <label for="idTipoCarta" class="col-sm-3 col-form-label">Tipo carta:</label>
                    <div class="sm-8">
                        <select class="form-select" name="idTipoCarta" aria-label="select" required>
                        <option selected disabled value="">Escoge un tipo de carta...</option>
                        <?php 
                            
                            foreach ($tiposCartaList as $carta) {
                                $selected="";
                                if($tipoCarta['idTipoCarta'] == $carta['id']){
                                    $selected="selected";
                                }
                        ?>
                            <option value="<?php echo $carta['id'] ?>" <?php echo $selected ?>><?php echo $carta['nombre'] ?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="col-sm-12 col-form-label lbl-ataque">P/T:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-control form-control-sm num-mana" type="text" name="ataque" value="<?php echo $tipoCarta['ataque'] ?>" aria-label="input example"> / 
                        <input class="form-control form-control-sm num-mana" type="text" name="defensa" value="<?php echo $tipoCarta['defensa'] ?>" aria-label="input example">
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a class="btn-cancel" href="list_cards.php"><button type="button" class="btn btn-secondary justify-content-md-end" name="cancelar">Cancelar</button></a>
                    <button type="submit" class="btn btn-primary justify-content-md-end" id="actualizar" name="actualizar">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>