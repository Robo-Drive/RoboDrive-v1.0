<?php if(isset($usuario)):?>
    <div class="">
        <h1><?= $usuario->getNome() ?></h1>
        <p><?= $usuario->getTipo() ?></p>
    </div>
<?php endif;?>