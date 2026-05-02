<?php
$titulo = "Página inicial";
$header = "CRUDS";
$rotas = ["usuario","projeto","equipe","componente","forum"];

foreach($rotas as $r)
{
    $menu[] = [
        "rota" => URL_BASE."/".$r."/listar",
        "nome" => "CRUD ".ucfirst($r)
    ];
}

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full ">
</div>
<?php
$marquee = "Listagem de projetos do projeto Robo Drive";
include_once(__DIR__."/../elements/footer.php");