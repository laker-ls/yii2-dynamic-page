<?php

use lakerLS\dynamicPage\models\Article;

/**
 * @var Article $article
 */

?>

<h1>Это статья, которая находится в категории.</h1>

<h1><?= $article->name ?></h1>
<p>
    <?= $article->url ?>
</p>