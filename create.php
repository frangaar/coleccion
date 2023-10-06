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

				let thumb = document.getElementsByClassName('img-thumbnail create')[0]; 

				thumb.style.display = 'block';
				const splitedWord = imagen.value.split('\\');
				thumb.src = 'img/'+splitedWord[2];
			}
		});
});
      
</script> -->
<body>
<?php session_start(); ?>
<?php include_once('menu.php'); ?>
<?php include_once('db.php') ?>
<div class="container mt-3">
    <div class="card margin-top-mobile-cards">
        <div class="card-body">
            <h5 class="card-title">Formulario de modificación de la carta</h5>
            <form action="controller.php" method="post" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="nombreCarta" class="col-sm-3 col-form-label">Nombre de la carta:</label>
                <div class="col-sm-12">
                    <input class="form-control" type="text" name="nombreCarta" id="nombreCarta" aria-label="input example">
                </div>
            </div>
            <div class="mb-3 float-left">
                    <label for="formFile" class="form-label">Imagen</label>
                    <input class="form-control" type="file" id="formFile" name="imgCarta" required>
                </div>
                <!-- <div class="mb-3">
                    <img  class="img-thumbnail create" id="img-preview">
                </div> -->
            <div class="mb-3 row clear">
                <label for="descCarta" class="col-sm-3 col-form-label">Descripción de la carta:</label>
                <div class="sm-8">
                    <textarea class="form-control" name="descCarta" id="descCarta" value="<?php isset($card['descripcion']) ? $card['descripcion'] : "" ?>" rows="3"></textarea>
                </div>
            </div>
            
            <?php

              $conn=openDB();

              $espansionListSql = "select id,nombreExpansion from expansiones";
              $expansionList = $conn->prepare($espansionListSql);
              $expansionList->execute();
          
              $expansionList = $expansionList->fetchAll();

              $tiposCartaListSql = "select id,nombre from tipo_carta order by id";
              $tiposCartaList = $conn->prepare($tiposCartaListSql);
              $tiposCartaList->execute();
          
              $tiposCartaList = $tiposCartaList->fetchAll();

              $listaTipoManaSql = "select id,nombre from tipo_mana order by id asc";            
              $listaTipoMana = $conn->prepare($listaTipoManaSql);
              $listaTipoMana->execute();

              $listaTipoManaInfo = $listaTipoMana->fetchAll();

              $conn=closeDB();
            ?>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Expansion:</label>
              <div class="sm-8">
                <select class="form-select" name="idExpansion" aria-label="select" required>
                <option selected disabled value="">Escoge una coleccion...</option>
                  <?php 
                    foreach ($expansionList as $value) {
                  ?>
                      <option value="<?php echo $value['id'] ?>"><?php echo $value['nombreExpansion'] ?></option>
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
                ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label" for="flexCheckDefault_<?php echo $tipo['id'] ?>"><?php echo $tipo['nombre'] ?></label>
                    <input class="form-control form-control-sm num-mana" type="text" name="values[<?php echo $tipo['id'] ?>]" aria-label="input example">
                    <input class="form-check-input" name="mana[<?php echo $tipo['id'] ?>]" type="checkbox" value="<?php echo $tipo['id'] ?>" id="flexCheckDefault_<?php echo $tipo['id'] ?>">
                </div>
                <?php 
                  } 
                ?>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Tipo carta:</label>
              <div class="sm-8">
                <select class="form-select" name="idTipoCarta" aria-label="select" required>
                <option selected disabled value="">Escoge un tipo de carta...</option>
                  <?php 
                    foreach ($tiposCartaList as $carta) {
                  ?>
                      <option value="<?php echo $carta['id'] ?>"><?php echo $carta['nombre'] ?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>
            
            <div class="mb-3">
                <label class="col-sm-12 col-form-label lbl-ataque">P/T:</label>
                <div class="form-check form-check-inline">
                    <input class="form-control form-control-sm num-mana" type="text" name="ataque" aria-label="input example"> / 
                    <input class="form-control form-control-sm num-mana" type="text" name="defensa" aria-label="input example">
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn-cancel" href="list_cards.php"><button type="button" class="btn btn-secondary justify-content-md-end" name="cancelar">Cancelar</button></a>
                <button type="submit" class="btn btn-primary justify-content-md-end" name="guardar">Guardar</button>
            </div>
      </form>
    </div>
  </div>  
</div>
</body>
</html>