<?php
declare(strict_types=1);

namespace lakerLS\dynamicPage\controllers\crud;

use Yii;

/**
 * Запись сообщений в сессию и отображение в представлении.
 *
 * Trait AlertTrait
 * @package app\traits
 */
trait AlertTrait
{
    /**
     * Передача алерт сообщения из шаблона с результатом работы для экшенов CRUD.
     *
     * @param string $status value 'success', 'info', 'warning', 'danger'.
     * @param string $action value 'create' or 'update'.
     * @param array|null $errorMessage
     * @return string
     */
    public function alert(string $status, string $action, ?array $errorMessage = null) : string
    {
        if ($errorMessage != null) {
            $message = $this->messageError($errorMessage);
        } else {
            $message = $this->alertMessage($status, $action);
        }

        Yii::$app->session->setFlash($status, $message, false);

        return $message;
    }

    /**
     * Передача произвольного алерт сообщения.
     *
     * @param string $status value 'success', 'info', 'warning', 'danger'.
     * @param string $message
     * @param array|null $errorMessage
     * @return string
     */
    public function alertCustom(string $status, string $message, ?array $errorMessage = null) : string
    {
        if ($errorMessage != null) {
            $message = $this->messageError($errorMessage);
        }

        Yii::$app->session->setFlash($status, $message, false);

        return $message;
    }

    /**
     * Отображение алерта в представлении.
     *
     * @return string
     */
    public static function displayAlert() : string
    {
        $allAlerts = ['success', 'info', 'warning', 'danger'];
        ob_start();
        for ($key = 0; $key < count($allAlerts); ++$key) :
            if (Yii::$app->session->hasFlash($allAlerts[$key])) :
                $style = $allAlerts[$key];
                $message = Yii::$app->session->getFlash($allAlerts[$key]);

                echo Yii::$app->view->render('//components/_alert', [
                    'style' => $style,
                    'message' => $message,
                ]);

            endif;
        endfor;

        return ob_get_clean();
    }

    /**
     * Отображение первой ошибки валидации.
     *
     * @param array|null $errors
     * @return string
     */
    private function messageError(array $errors) : string
    {
        $firstError = array_shift($errors);

        return 'Ошибка: ' . $firstError[0];
    }

    /**
     * Шаблон с сообщениями алертов.
     *
     * @param string $status
     * @param string $action
     * @return string
     */
    private function alertMessage(string $status, string $action) : string
    {
        if ($action == 'create') {
            $move = 'создана';
        } elseif ($action == 'update') {
            $move = 'обновлена';
        } elseif ($action == 'delete') {
            $move = 'удалена';
        } else {
            throw new \Exception('Не корректное значение $action');
        }

        if ($status == 'success') {
            $message = "Запись успешно $move!";
        } elseif ($status == 'warning') {
            $message = "Запись была $move некорректно!";
        } elseif ($status == 'danger') {
            $message = "Ошибка! Запись не была $move! Проверьте корректность данных.";
        } else {
            throw new \Exception('Не корректное значение $status');
        }

        return $message;
    }
}