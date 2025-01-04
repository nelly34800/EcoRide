<?php
require_once "templates/header.php";
?>

<div class="hero-scene">
    <img src="assets/img/BanFumee.jpg" alt="" width="100%">
</div>

<h1>Contactez-nous!</h1>

<form action="" method="$_POST">
    <div class="mb3">
        <label for="">motif : </label>
        <input type="text" name>
    </div>
    <div class="mb3">
        <label for="description" class="form-label">Description : </label>
        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
    </div>
</form>

<?php
require_once "templates/footer.php";
?>