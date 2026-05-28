<?php
$titulo = "Página inicial";
$header = "RoboDrive";

include_once(__DIR__."/../elements/header.php");
?>
<div 
    class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12 bg-cover bg-center bg-no-repeat text-white"
    style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');"
>

    <div class="col-span-6 row-span-9 flex flex-col justify-center items-center">
        <h1 class="text-8xl font-black tracking-widest">REPOSITÓRIO</h1>
        <h1 class="text-8xl font-black tracking-widest">DE PROJETOS</h1>
        <h1 class="text-8xl font-black tracking-widest">DA ROBÓTICA</h1>
    </div>

    <div class="col-span-6 row-span-9 flex flex-col justify-center p-10 bg-black/30">
        <h1 class="text-3xl font-bold mb-4 font-['Orbitron']">
            SOBRE O PROJETO
        </h1>

        <p class="text-justify text-lg">
            O RoboDrive é uma plataforma desenvolvida para resolver o problema da dispersão de conhecimento em projetos de robótica educacional. Quando códigos, documentações e decisões técnicas ficam espalhados em dispositivos pessoais e mensagens, perde-se a capacidade de reaproveitar esse conhecimento em turmas futuras.
        </p>
    </div>
    <div class="col-span-6 row-span-3 flex flex-col justify-center items-center ]">
        <a href="<?= URL_BASE."/login" ?>" class="border-2 px-[60px] py-[40px] text-3xl bg-black">ENTRE AGORA -></a>
    </div>

    <div class="col-span-6 row-span-3 flex flex-col justify-center items-center gap-2">
        <div class="flex justify-center items-center gap-2">
            <div class="w-[450px] h-[100px] px-4 py-2 border-2 bg-black">
                <h1 class="text-center font-bold  font-['Orbitron']">Projetos</h1>
                <hr>
                <p>Compartilhe seus projetos.</p>
            </div>
            <div class="w-[450px] h-[100px] px-4 py-2 border-2 bg-black">
                <h1 class="text-center font-bold  font-['Orbitron']">Componentes</h1>
                <hr>
                <p>Catalogue os componentes de seus projetos.</p>
            </div>
        </div>
        <div class="flex justify-center items-center gap-2">
            <div class="w-[450px] h-[100px] px-4 py-2 border-2 bg-black">
                <h1 class="text-center font-bold  font-['Orbitron']">Equipe</h1>
                <hr>
                <p>Junte-se a equipes para aprender em conjunto.</p>
            </div>
            <div class="w-[450px] h-[100px] px-4 py-2 border-2 bg-black">
                <h1 class="text-center font-bold  font-['Orbitron']">Fórum</h1>
                <hr>
                <p>Discuta e compartilhe conhecimento.</p>
            </div>
        </div>
    </div>

</div>
<?php
include_once(__DIR__."/../elements/footer.php");