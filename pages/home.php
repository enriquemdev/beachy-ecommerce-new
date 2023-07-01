<?php
require_once "main/public/top.php";



// echo '<script>';
//             echo 'console.log('. json_encode( $_SESSION['nombresCliente'] ) .')';
//             echo '</script>';
?>
<!-- <link rel="stylesheet" type="text/css" href="../botman/chat.min.css"> -->

<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

        <div class="carousel-item active">

            <div class="carrusel" style="left: 35%;">
                <!-- <h1>Shorts de Baño</h1> -->
                <a class="anotstyle" href="<?= SERVERURL ?>pages/products.php"><button class="btn btn-info">
                        <h5>Ver Productos</h5>
                    </button></a>
            </div>

            <img src="../img/c3.jpg" class="d-block w-100 imgCarrusel" alt="..." height="750px">
        </div>

        <div class="carousel-item">

            <div class="carrusel" style="left: 75%;">
                <a class="anotstyle" href="<?= SERVERURL ?>pages/products.php"><button class="btn btn-info">
                        <h5>Ver Productos</h5>
                    </button></a>
            </div>

            <img src="../img/c5.jpg" class="d-block w-100 imgCarrusel" alt="..." height="750px">
        </div>

        <div class="carousel-item">

            <div class="carrusel" style="left: 75%;">
                <a class="anotstyle" href="<?= SERVERURL ?>pages/products.php"><button class="btn btn-info">
                        <h5>Ver Productos</h5>
                    </button></a>
            </div>

            <img src="../img/c1.jpg" class="d-block w-100 imgCarrusel" alt="..." height="750px">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="container-fluid">
    <div class="row cardTitles">
        <h2>Productos de la Temporada de Verano</h2>
    </div>

    <div id="idCards" class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card h-100 text-center">
                <img src="../img/t (11).png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Vineyard Vines OG</h5>
                    <p class="card-text">
                        La camiseta ideal para cada ocasion! Estilo Long-Sleeve.
                    </p>

                    <a href="<?= SERVERURL ?>pages/producto.php?codProd=1" class="btn btn-primary">Ver</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 text-center">
                <img src="../img/imgProductos/CamRosStaVin3/main.jpeg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Vineyard Vines camiseta rosada</h5>
                    <p class="card-text">Un estilo deslumbrante en una maravillosa comodidad.</p>
                    <a href="<?= SERVERURL ?>pages/producto.php?codProd=3" class="btn btn-primary">Ver</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 text-center">
                <img src="../img/imgProductos/CamBlaStaVin4/main.jpeg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Vineyard Vines White Palms</h5>
                    <p class="card-text">La camiseta por excelencia en comodidad y estilo!</p>
                    <a href="<?= SERVERURL ?>pages/producto.php?codProd=4" class="btn btn-primary">Ver</a>
                </div>
            </div>
        </div>

    </div><!--End of cards container-->

</div>

<div class="row">
    <img src="../img/fila.jpg" alt="fila vineyard" class="imagenFila">
</div>

<div class="container-fluid">
    <div class="row cardTitles">
        <h2>Best Sellers</h2>
    </div>

    <div id="idCards" class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card h-100 text-center">
                <img src="../img/imgProductos/CamCelStaBro6/main.jpeg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Brooks Brothers TShirt Light Blue</h5>
                    <a href="<?= SERVERURL ?>pages/producto.php?codProd=6" class="btn btn-primary">Ver</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 text-center">
                <img src="../img/imgProductos/AccRojStaVin2/main.jpeg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Vineyard Vines Gorra trucker</h5>
                    <a href="<?= SERVERURL ?>pages/producto.php?codProd=2" class="btn btn-primary">Ver</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 text-center">
                <img src="../img/t (2).png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Red Brooks brother tshirt</h5>
                    <a href="<?= SERVERURL ?>pages/producto.php?codProd=7" class="btn btn-primary">Ver</a>
                </div>
            </div>
        </div>



    </div><!--End of cards container-->


</div>


<script>
    let aTag = document.getElementById("linkHome")
    aTag.classList.add("active");
</script>

<?php
require_once "main/public/bottom.php";
?>