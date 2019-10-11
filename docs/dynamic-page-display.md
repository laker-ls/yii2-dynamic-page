# Отображение динамических страниц

## Отображение категорий

В представлении категорий (viewCategory) вы можете использовать следующие переменные по умолчанию:
    `$category` - Объект текущей категории.
    `$dataProvider` - Массив объектов со всеми статьями, которые находятся в текущей категории.
    `$this->categories` - Массив объектов всех существующих категорий.
 
## Отображение статей

В представлении статей (viewArticle) вы можете использовать следующие переменные по умолчанию:
    `$category` - Объект категории, в которой находится статья.
    `$article` - Объект текущей статьи.
    `$this->categories` - Массив объектов всех существующих категорий.
    
## Передача дополнительных параметров

Вы можете передавать свои параметры через события в следующие action контроллера `DynamicPageController`: `actionCategory` и `actionArticle`.

Пример передачи данных в action из дочернего контроллера (отнаследованного):

```php
public function actionCategory(Category $model, $redirect = null)
{
    $this->on(parent::EVENT_BEFORE_ACTION, function () {
        $this->addParamsCategory = [
            'myParams' => $myParams',
            'more' => $more,
        ];
    });

    return parent::actionCategory($model, $redirect);
}
```

Используйте свойство `addParamsCategory` для передачи параметров в `actionCategory`. 
Для передачи в `actionArticle` используйте свойство `addParamsArticle`.

Другие варианты использования событий читайте [здесь](https://klisl.com/yii2_events.html).