# Переопределение прав доступа

По умолчанию используется роль `admin` для получения доступа. Вы можете переназначить эту роль следующим образом:

В файле `config-web` указываем необходимые нам роли.
```php
'modules' => [
    'dynamic-page' => [
        'class' => 'lakerLS\dynamicPage\Module',
        'params' => [
            'accessRoles' => ['example', 'more-roles'],
        ]
    ],
]
```