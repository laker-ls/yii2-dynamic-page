<?php

namespace lakerLS\dynamicPage\controllers;

use developeruz\db_rbac\behaviors\AccessBehavior;
use kartik\tree\controllers\NodeController;
use lakerLS\dynamicPage\components\Access;
use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\search\CategorySearch;
use lakerLS\dynamicPage\validators\CategoryUrlValidator;
use Yii;

/**
 * CategoryController реализует CRUD модели Category.
 */
class CategoryController extends NodeController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'as AccessBehavior' => [
                'class' => AccessBehavior::class,
                'rules' => [
                    'dynamic-page/category' => [
                        [
                            'allow' => true,
                            'roles' => Access::getRoles(),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображение и управление всеми категориями.
     * @return mixed
     */
    public function actionIndex()
    {
        $treeViewModel = new CategorySearch();
        $treeView = $treeViewModel->treeView();
        $anyCategory = ModelMap::findByName('Category')->one();

        if (!empty($anyCategory)) {
            $treeCheck = false;
        } else {
            $treeCheck = true;
        }

        return $this->render('index', [
            'treeView' => $treeView,
            'treeCheck' => $treeCheck,
            'anyCategory' => $anyCategory,
        ]);
    }

    /**
     * Переопределенный action TreeView от kartik-v.
     * Если результатом стандартного экшена является вывод ошибки, переопределяем сообщение об ошибке.
     */
    public function actionMove()
    {
        $result =  parent::actionMove();

        if ($result['status'] == 'error') {
            $post = Yii::$app->request->post();

            $model = ModelMap::findByName('Category')->where(['id' => $post['idFrom']])->one();

            $validate = new CategoryUrlValidator();
            $validate->validateAttribute($model, 'url');

            $result['out'] = $model->errors['url'][0];
        }

        return $result;
    }
}
