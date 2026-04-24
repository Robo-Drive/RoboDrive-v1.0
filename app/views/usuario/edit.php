<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <form action="<?= URL_BASE?>/usuario/editar?>" method="post">
        <?php include_once(__DIR__."/elements/form.php")?>
    </form>
</body>
</html>