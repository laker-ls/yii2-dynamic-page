# Подключение

## 1. Подключение модуля

В конфиге по пути `config/web` для basic версии приложения подключите модуль:
```php
'modules' => [
    'dynamic-page' => [
        'class' => 'lakerLS\dynamicPage\Module',
    ],
    'treemanager' =>  [ // модуль для работы с деревом категорий.
        'class' => '\kartik\tree\Module',
    ]
]
```

## 2. Подключение в urlManager

В конфиге по пути `config/web` для basic версии приложения, в `urlManager` подключаем правила для динамических страниц.
После чего приложение будет искать прежде всего статическую страницу по указанному адресу, если таковой нет, поиск
осуществляется в динамических страницах.

> ВАЖНО: все переопределения путей совершать перед подключением динамических страниц.

```php
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // Переопределение путей.
        ['class' => 'lakerLS\dynamicPage\components\DynamicPage'], // Подключение модуля
    ],
],
```

## 3. Подключение/Перемещение jQuery в начало страницы.

В админ-панели, где производится управление категориями jQuery должен быть подключен в начало страницы.
Для этого вы можете переопределить jQuery в `config/web` для basic версии приложения следующим образом:

```php
'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_BEGIN], // Указываем позицию
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', // Путь к вашему jQuery
                    ]
                ],
            ],
        ],
```

Так же вы можете задать расположение для всех js файлов в `AssetBundle`, где подключены все js файлы для вашего 
приложения (админ-панели) следующим образом:

> ВАЖНО: Данный вариант не является рекомендуемым.

```php
public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
```

## 4. Отключение bootstrap модуля

По умолчанию расширение подключает 3 версию bootstrap.

Вероятнее всего вы используйте bootstrap в своем проекте, т.е. он подключен в зависимостях вашего основного `AssetBundle`,
в таком случае необходимо отключить bootstrap модуля, для этого в `config/params` установите следующее свойство:

```php
return [
    'bsDependencyEnabled' => false,
];
```

В случае отключения bootstrap модуля, вы должны следить за тем, что бы в вашем `AssetBundle` была установлены следующие 
зависимости:
```php
public $depends = [
    'yii\bootstrap\BootstrapAsset',
    'yii\bootstrap\BootstrapPluginAsset',
];
```

# Дополнительная настройка

## Переопределение layout

Изначально используется layout установленный по умолчанию для проекта.

Переопределение layout для всего модуля:

```php
'dynamic-page' => [
    'class' => 'lakerLS\dynamicPage\Module',
    'layout' => '@app/views/layouts/admin', // путь к Вашему layout
],
```

Для изменения layout конкретного контроллера необходимо переопределить контроллер и передать свойство `$layout`,
данный пример лишь одно из возможных решений:

```php
class DynamicPageController extends BaseDynamicPageController
{
    public $layout = '@app/views/layouts/site'; // путь к к Вашему layout
}
```

Подробнее о переопределении контроллера читайте [здесь](https://github.com/laker-ls/yii2-dynamic-page/blob/master/docs/overriding-controllers.md).