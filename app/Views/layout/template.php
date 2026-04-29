<html>
    <head> 
        <title>Projekt pro dva lidi</title>
        <?= $this->include("layout/assets");?> 
 </head> 
 <body>
 <?= $this->include("layout/navbar");?>
 <!--Dynamický obsah -->
 <div class="container">
 <?= $this->renderSection("content"); ?> 
 </div>
 <script src="<?= base_url("node_modules/bootstrap/dist/js/bootstrap.bundle.min.js") ?>"></script>
</body>
</html>

