# Установка

## 1. Подготовка

Для работы данного расширения необходима настройка [yii2-user]() и [yii2-rbac](), 
подробнее о настройке и использовании данных расширений смотрите [здесь]() и [здесь]().

Если в вашем проекте установлены расширения указанные выше и существует пользователь с ролью `admin` можете приступать
к установке данного расширения.

## 2. Установка

Рекомендуемый способ установки этого расширения является использование [composer](http://getcomposer.org/download/).
Проверьте [composer.json](https://github.com/laker-ls/yii2-dynamic-page/blob/master/composer.json) на предмет требований и зависимостей данного расширения.

Для установки запустите

```
$ php composer.phar require laker-ls/yii2-dynamic-page "~1.0.1"
```

или добавьте в `composer.json` в раздел `require` следующую строку

```
"laker-ls/yii2-dynamic-page": "~1.0.1"
```

> Смотрите [список изменений](https://github.com/laker-ls/yii2-dynamic-page/blob/master/CHANGE.md) для подробной информации о версиях.

Выполните миграции в консоли:
```
yii migrate --migrationPath=@lakerLS/dynamicPage/migrations
```