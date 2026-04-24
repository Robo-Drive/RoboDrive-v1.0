<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Componente</title>
</head>
<body>
    <form action="<?= URL_BASE?>/componente/salvar?>" method="post">
        <?php include_once(__DIR__."/elements/form.php")?>
    </form>
</body>
</html>