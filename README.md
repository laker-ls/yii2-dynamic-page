<h1 align="center">
    yii2-dynamic-page
</h1>


[![Stable Version](https://poser.pugx.org/laker-ls/yii2-dynamic-page/v/stable)](https://packagist.org/packages/laker-ls/yii2-dynamic-page)
[![Unstable Version](https://poser.pugx.org/laker-ls/yii2-dynamic-page/v/unstable)](https://packagist.org/packages/laker-ls/yii2-dynamic-page)
[![License](https://poser.pugx.org/laker-ls/yii2-dynamic-page/license)](https://packagist.org/packages/laker-ls/yii2-dynamic-page)
[![Total Downloads](https://poser.pugx.org/laker-ls/yii2-dynamic-page/downloads)](https://packagist.org/packages/laker-ls/yii2-dynamic-page)

> ВНИМАНИЕ: Для работы необходим Rbac с существующей ролью.

Реализация динамических страниц, которые создаются с помощью CRUD. Каждая динамическая страница является категорией или
статьей. Категория может содержать в себе вложенности (статьи/категории). Статьи не могут содержать в себе вложенности.

Подробная документация по данному расширению [здесь]().

## Установка

Рекомендуемый способ установки этого расширения является использование [composer](http://getcomposer.org/download/).
Проверьте [composer.json](https://github.com/laker-ls/yii2-dynamic-page/blob/master/composer.json) на предмет требований и зависимостей данного расширения.

Для установки запустите

```
$ php composer.phar require laker-ls/yii2-dynamic-page "~1.0.0"
```

или добавьте в `composer.json` в раздел `require` следующую строку

```
"laker-ls/yii2-dynamic-page": "~1.0.0"
```

> Смотрите [список изменений](https://github.com/laker-ls/yii2-dynamic-page/blob/master/CHANGE.md) для подробной информации о версиях.

Выполните миграции в консоли:
```
yii migrate --migrationPath=@lakerLS/dynamicPage/migrations
```

## Подключение

В конфиге приложения подключите модуль:
```php
'modules' => [
    'dynamic-page' => [
        'class' => '\lakerLS\dynamic-page\Module',
    ],
]
```

В `urlManager` подключаем правила для динамических страниц, после чего приложение будет искать прежде всего 
статическую страницу по указанному адресу, если таковой нет, поиск осуществляется в динамических страницах.

> ВАЖНО: все переопределения путей совершать перед подключением динамических страниц.

```php
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // Переопределение путей.
        ['class' => 'lakerLS\dynamicPage\components\DynamicPage'],
    ],
],
```

В админ-панели, где производится управление категориями jQuery должен быть подключен в начало страницы.
Для этого вы можете переопределить jQuery в `config/web` следующим образом:

```php
'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_BEGIN],
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', // Указать путь к вашему jQuery
                    ]
                ],
            ],
        ],
```

Так же вы можете задать расположение для всех js файлов в `AssetBundle`, где подключены все js файлы для вашего приложения (админ-панели)
следующим образом:

> ВАЖНО: Данный вариант не является рекомендуемым.

```php
public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
```

Расширение готово к работе.

## Использование модуля

Читайте раздел "[Использование](https://github.com/laker-ls/yii2-dynamic-page/blob/master/docs/README.md)" в подробной документации.

## Переопределение моделей, контроллеров, представлений

Для каждого переопределения существует подробная документация:

- [Переопределение моделей](https://github.com/laker-ls/yii2-dynamic-page/blob/master/docs/overriding-models.md)
- [Переопределение контроллеров](https://github.com/laker-ls/yii2-dynamic-page/blob/master/docs/overriding-controllers.md)
- [Переопределение представлений](https://github.com/laker-ls/yii2-dynamic-page/blob/master/docs/overriding-actions.md)

## Использование bootstrap4

Если используется bootstrap4, вы должны установить расширение [bootstrap4 for yii2](https://github.com/yiisoft/yii2-bootstrap4) 
и прописать в `config\params`:
```php
return [
    'bootstrapVersion' => 4,
]
```

Так же в вашем `AssetBundle` заменить зависимости с версии 3 на 4 следующим образом:

```php
public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset', // подключение Bootstrap
    ];
```

## Лицензия

**yii2-pencil** выпущено по лицензии BSD-3-Clause. Ознакомиться можно в файле `LICENSE.md`.
