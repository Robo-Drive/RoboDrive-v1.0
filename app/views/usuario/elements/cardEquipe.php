<div class="w-full border border-zinc-700 overflow-x-auto  p-3 shadow-xl">
    <div class="flex items-center gap-4">
        <?php if(isset($equipes)):?>
        <?php foreach($equipes as $equipe): ?>
            <a
            href="<?= URL_BASE ?>/equipe/perfil?id=<?= $equipe->getId() ?>"
            class="hover:border-[#00F5F5]  px-5 py-2 text-white font-bold border"
            >
                <h1 class="text-xl font-black text-white ">
                    <?= $equipe->getNome() ?>
                </h1>
            </a>
        <?php endforeach; ?>  
        <?php else: ?> 
            <h1 class="w-full text-center">Você não faz parte de nenhuma equipe</h1> 
        <?php endif;?>
    </div>

</div>
