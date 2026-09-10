<?php if(isset($projeto)):?>
<div class="rd-content rd-scroll-hidden">

    <section class="rd-section">
        <p class="rd-eyebrow"><?= ucfirst($projeto->getVisibilidade() ?? '') ?></p>
        <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]"><?= $projeto->getNome() ?></h1>
    </section>

    <section class="rd-section flex flex-col gap-6">
        <div class="rd-card">
            <p class="rd-eyebrow">Descrição</p>
            <p class="text-sm leading-relaxed text-[#F2FEFE]"><?= $projeto->getDescricao() ?></p>
        </div>

        <div class="rd-card">
            <p class="rd-eyebrow">Componentes</p>
            <div class="flex flex-wrap gap-4 pt-2">
                <?php foreach($projeto->getComponentes() as $componente): ?>
                    <?php include(__DIR__."/cardComponente.php");?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="rd-card">
            <p class="rd-eyebrow">Desenvolvedores</p>
            <div class="flex flex-wrap gap-4 pt-2">
                <?php if(isset($usuarios)): ?>
                    <?php foreach($usuarios as $usuario): ?>
                        <?php include(__DIR__."/cardUsuario.php");?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="rd-section flex flex-wrap justify-center gap-4">
        <form action="<?= URL_BASE ?>/projeto/editar" method="post">
            <input type="hidden" name="id" value="<?= $projeto->getId() ?>">
            <button type="submit" class="rd-btn rd-btn-primary">Editar projeto</button>
        </form>
        <a href="<?= URL_BASE ?>/projeto" class="rd-btn rd-btn-secondary">Voltar</a>
    </section>

</div>
<?php endif;?>
