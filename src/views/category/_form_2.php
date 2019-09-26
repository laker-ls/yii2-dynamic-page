<?php

use lakerLS\dynamicPage\components\ModelMap;
use yii\helpers\ArrayHelper;


/* @var $node yii\base\Model */
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

?>


<?= $form->field($node, 'id')->textInput()->hiddenInput()->label(false)?>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($node, 'name')->textInput(['class' => 'form-control translate-of'])?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($node, 'url')->textInput(['class' => 'form-control translate-in'])?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($node, 'type')->dropDownList(
            ArrayHelper::map($typeDropDown = ModelMap::findByName('Type')
                ->where(['category' => '1'])->orderBy(['position' => SORT_DESC])->asArray()->all(),
                'type', 'name')
        ) ?>
    </div>
    <div>
        <div class="col-sm-6" style="padding-top: 25px">
            <a href="#" class="btn btn-success image-category" style="width: 100%">Изображение / Развернуть</a>
        </div>

        <div class="col-sm-12" name="window-image" style="display: none;">
            <?= $form->field($node, 'image')->textarea(); ?>
        </div>

        <script>
            $(".image-category").on("click", function (event) {
                let mainWindow = $("div[name='window-image']");
                event.preventDefault();

                if (mainWindow.css("display") === "none") {
                    $(this).html("Изображение / Свернуть");
                    $(this).css("background-color", "#008eff");
                    mainWindow.show();
                } else {
                    $(this).html("Изображение / Развернуть");
                    $(this).css("background-color", "#5cb85c");
                    mainWindow.hide();
                }
            });
        </script>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?= $form->field($node, 'title')->textInput(['maxlength' => true]);?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($node, 'description')->textarea(['rows' => 4, 'maxlength' => true]);?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($node, 'keyword')->textarea()?>
    </div>
</div>


<script>
    //Ckeditor.add("category", ["image"]);
</script>