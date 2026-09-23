<div class="rd-card">
    <div class="flex flex-wrap gap-3">
        <?php if(isset($equipes)):?>
        <?php foreach($equipes as $equipe): ?>
            <a href="<?= URL_BASE ?>/equipe/perfil?id=<?= $equipe->getId() ?>" class="rd-chip">
                <?= $equipe->getNome() ?>
            </a>
        <?php endforeach; ?>
        <?php else: ?>
            <p class="rd-empty">Você não faz parte de nenhuma equipe</p>
        <?php endif;?>
    </div>
</div>
