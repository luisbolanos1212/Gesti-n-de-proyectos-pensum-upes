<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Nav;
use yii\widgets\Breadcrumbs;
use app\assets\UpesAsset;
use yii\helpers\Url;

UpesAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
   
    echo 
      Html::beginTag(
        'nav', 
        [
          'id' => 'pensum-search',
          'class' => 'navbar'// navbar-dark bg-dark'
        ]
      ) .
      Html::beginTag('a', ['class' => 'navbar-brand text-white']) . 'Universidad Politécnica de El Salvador' . Html::endTag('a'),
      Html::beginForm(
              '', 
              'post', 
              [
                'class' => 'form-inline',
                'action' => Url::to(['pensum/buscar'])
              ]
      ) .
      Html::input(
        'text', 
        'carnet', 
        '',
        [ 
          'class' => 'form-control mr-sm-2',
          'placeholder' => 'Carnet'
        ]
      ) . 
      Html::endForm() .
      Html::endTag('nav');      

    ?>
    <div class="container">
 
        <?= $content ?>
    </div>

</div>

<footer class="footer">
    
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
