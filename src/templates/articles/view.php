<?php include __DIR__ . '/../header.php'; ?>
    <h2><?php echo $article->getName() ?></h2>
    <p><?php echo $article->getParsedText(); ?></p>
    <p>Автор: <?php echo $article->getAuthor()->getNickname(); ?></p>
    <?php if ($user !== null && $user->isAdmin()):?>
        <button><a style="text-decoration: none" href="/articles/<?php echo $article->getId();?>/edit"">Редактировать</a></button>
    <?php endif; ?>
<?php include __DIR__ . '/../footer.php'; ?>