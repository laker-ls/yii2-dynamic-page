# Переопределение представлений

Практически всегда, Вам необходимо переопределить представления для корректного отображения.

## 1. Перемещение представлений

Прежде всего <b>скопируйте</b> существующие представления из папки <b>/vendor/laker-ls/yii2-dynamic-page/src/views</b>
в необходимую вам папку, по умолчанию для basic версии путь будет <b>/views</b>.

Для удобства вы можете увеличить вложенность. Пример: <b>/views/dynamic-page</b>.

## 2. Переопределение путей

Обратите внимание на то, что переопределение путей для представления отличается от переопределения моделей и контроллеров.
Представления переопределяются в `components`, когда модели и контроллеры в `modules`.

В `config/web` для basic версии необходимо указать пути для представлений, которые будут переопределены.

```php
'components' => [
    'view' => [
        'theme' => [
            'pathMap' => [
                '@lakerLS/dynamicPage/views' => '@app/views/dynamic-page'
            ],
        ],
    ],
]
```

При переопределении указываются не пространства имен (т.к. их не существует для представления), а физический путь.
Для удобства указывается путь через `alias`.

> ВНИМАНИЕ: В данном примере переопределены все существующие представления, но вы можете переопределять только
необходимые представления, указывая конкретный путь.