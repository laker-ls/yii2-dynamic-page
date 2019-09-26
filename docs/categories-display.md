# Отображение категорий

Категории отображаются вложенным списком. Для построения такого списка используйте расширение 
[yii2-nested-set-menu](https://github.com/laker-ls/yii2-nested-set-menu), которое идет в комплекте:

## Отображение меню

Обязательный параметр allCategories, должен быть объектом и содержать в себе выборку из базы данных. 
Обязательными полями в базе данных являются: id, lft, rgt, lvl, name, url.

Остальные параметры являются не обязательными и используются для указания атрибутов тегам, к примеру class, style и другие. 
Для того, что бы присвоить к вложенному пункту меню иконку, передайте строкой классы иконки.

Пример использования виджета с вложенными пунктами меню:

```php
use lakerLS\nestedSet\Menu;
           
echo Menu::widget([
    'allCategories' => $allCategory,
    'options' => [
        'main' => [
            'ul' => ['class' => 'navbar-nav mr-auto', 'style' => 'margin-top: 20px'],
            'lonely' => [
                'li' => ['class' => 'nav-item'],
                'a' => ['class' => 'nav-link'],
            ],
            'hasNesting' => [
                'li' => ['class' => 'nav-item dropdown'],
                'a' => ['class' => 'nav-link dropdown-toggle'],
                'icon' => 'fa fa-arrow-bottom'
            ],
            'active' => ['class' => 'active'],
        ],
        'nested' => [
            'ul' => ['class' => 'dropdown-menu', 'data-toggle' => 'example'],
            'lonely' => [
                'li' => ['class' => 'dropdown-item'],
                'a' => ['class' => 'dropdown-link'],
            ],
            'hasNesting' => [
                'li' => ['class' => 'dropdown-item dropdown'],
                'a' => ['class' => 'dropdown-link dropdown'],
                'icon' => 'fa fa-arrow-right'
            ],
            'active' => ['class' => 'active', 'id' => 'example']
        ],
    ],
]);
```

Пример использования виджета без вложенных пунктов меню:

```php
use lakerLS\nestedSet\Menu;
           
echo Menu::widget([
    'allCategories' => $allCategory,
    'options' => [
        'main' => [
            'ul' => ['class' => 'navbar-nav mr-auto', 'style' => 'margin-top: 20px'],
            'lonely' => [
                'li' => ['class' => 'nav-item'],
                'a' => ['class' => 'nav-link'],
            ],
            'hasNesting' => [
                'li' => ['class' => 'nav-item dropdown'],
                'a' => ['class' => 'nav-link dropdown-toggle'],
                'icon' => 'fa fa-arrow-bottom'
            ],
            'active' => ['class' => 'active'],
        ],
    ],
]);
```

main - меню первого уровня, не вложенное в какие-либо категории.
nested - меню второго или ниже уровня, вложенное.

lonely - пункт меню, который НЕ имеет вложенных в него категорий.
hasNesting - пункт меню, который имеет вложенные в него категории.

active - указываем параметры для активного пункта меню, которые применятся к тегу li. Атрибуты наследуются от тега li, 
не нужно дублировать атрибуты в том числе и классы в параметре active.

Параметры для ul, li, a, active передаются массивом.
Параметры для icon передаются строкой.

## Создание представления

При переходе по ссылке пользователь попадает на представление категории, которое должно находиться в
`views/dynamic-page`. Подробности создания и указания имен представления, читайте в разделе 
[Типы](https://github.com/laker-ls/yii2-dynamic-page/blob/master/docs/types-management.md).