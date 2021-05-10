<ul class="paging">
    <?php if ($page > 1) : ?>
        <li><a class="pagenation" href="index.php?page=<?php print(htmlspecialchars($page - 1)) ?>">前のページへ</a></li>
    <?php endif ?>
    <?php if ($page < $maxPage) : ?>
        <li><a class="pagenation" href="index.php?page=<?php print(htmlspecialchars($page + 1)) ?>">次のページへ</a></li>
    <?php endif ?>
</ul>