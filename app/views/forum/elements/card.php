<?php if(isset($forum)):?>
<div class="bg-black/80 p-4 w-[90%] border">
    <p><?= $forum->getConteudo() ?></p>
</div>
<?php endif;?>