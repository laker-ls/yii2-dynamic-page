<?php

namespace lakerLS\dynamicPage\abstractClasses;

use developeruz\db_rbac\behaviors\AccessBehavior;
use lakerLS\dynamicPage\components\Access;
use lakerLS\dynamicPage\components\ModelMap;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrudController абстрактный класс, который реализует CRUD.
 */
abstract class CrudController extends Controller
{
    /**
     * Событие срабатывает после попытки создания модели.
     * Не важно, была ли попытка успешна или нет, сработает это событие.
     */
    const EVENT_AFTER_CREATE = 'afterCreate';

    /**
     * Событие срабатывает после попытки обновления модели.
     * Не важно, была ли попытка успешна или нет, сработает это событие.
     */
    const EVENT_AFTER_UPDATE = 'afterUpdate';

    /**
     * Событие срабатывает после попытки удаления модели.
     * Не важно, была ли попытка успешна или нет, сработает это событие.
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * Передаем экземпляр model текущего контроллера.
     *
     * @return object экземпляр model.
     */
    protected abstract function model();

    /**
     * Передаем экземпляр modelSearch текущего контроллера.
     *
     * @return object экземпляр modelSearch.
     */
    protected abstract function modelSearch();

    /**
     * Если пользователь авторизован как 'админ', он имеет доступ ко всем экшенам. Простым пользователям все экшены
     * закрыты.
     * Запрос на удаление записи должен передавать POST запросом.
     *
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $controller = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id;
        return [
            'as AccessBehavior' => [
                'class' => AccessBehavior::class,
                'rules' => [
                    $controller => [
                        [
                            'allow' => true,
                            'roles' => Access::getRoles(),
                        ],
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список всех моделей.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->modelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создание новой модели.
     * Если создание успешно, будет переадресация на страницу 'index'.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->model();
        $article = ModelMap::newObject('Article');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->alert('success', 'create');
            } else {
                $this->alert('danger', 'create');
            }
            $this->trigger(self::EVENT_AFTER_CREATE);

            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
            'article' => $article,
        ]);
    }

    /**
     * Обновление найденной по id модели.
     * Если обновление успешно, будет переадресация на страницу 'index'.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $article = ModelMap::newObject('Article');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->alert('success', 'update');
            } else {
                $this->alert('danger', 'update');
            }
            $this->trigger(self::EVENT_AFTER_UPDATE);

            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
            'article' => $article,
        ]);
    }

    /**
     * Удаление найденной по id модели.
     * Если удаление успешно, будет переадресация на страницу 'index'.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            $this->alert('success', 'delete');
        } else {
            $this->alert('danger', 'delete');
        }
        $this->trigger(self::EVENT_AFTER_DELETE);

        return $this->redirect(['index']);
    }

    /**
     * Виджет Position. Перемещение на позицию выше.
     * @param integer $id
     * @return \yii\web\Response|integer
     */
    public function actionMoveUp($id)
    {
        /** @var object $this */
        $result = $this->findModel($id)->moveNext();

        if (Yii::$app->request->post('ajax') == true) {
            return $result == 1 ? 1 : null;
        } else {
            return $this->goBack();
        }
    }

    /**
     * Виджет Position. Перемещение на позицию ниже.
     * @param integer $id
     * @return \yii\web\Response|integer
     */
    public function actionMoveDown($id)
    {
        /** @var object $this */
        $result = $this->findModel($id)->movePrev();

        if (Yii::$app->request->post('ajax') == true) {
            return $result == 1 ? -1 : null;
        } else {
            return $this->goBack();
        }
    }

    /**
     * Поиск базовой модели  по id.
     * Если модель не найдена, будет выброшена 404 ошибка.
     *
     * @param integer $id
     * @return object
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = $this->model()::findOne($id);

        if (($model) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Передаем алерт сообщение с результатом работы.
     *
     * @param string $status value 'success', 'danger', 'error'.
     * @param string $action value 'create' or 'update'.
     */
    protected function alert($status, $action)
    {
        $message = $this->alertMessage($status, $action);

        Yii::$app->session->setFlash($status, $message, false);
    }

    /**
     * Шаблон с сообщениями алертов.
     *
     * @param string $status
     * @param string $action
     * @return string
     */
    private function alertMessage($status, $action)
    {
        switch ($action) {
            case 'create':
                $move = 'создана';
                break;
            case 'update':
                $move = 'обновлена';
                break;
            case 'delete':
                $move = 'удалена';
                break;
            default:
                $move = '';
        }

        switch ($status) {
            case 'success':
                $message = "Запись успешно $move!";
                break;
            case 'warning':
                $message = "Запись была $move некорректно!";
                break;
            case 'danger':
                $message = "Ошибка! Запись не была $move! Проверьте корректность данных.";
                break;
            default:
                $message = 'Некорректно передан первый параметр "$status"';
        }

        return $message;
    }
}
