<?php

namespace lakerLS\dynamicPage\controllers;

use developeruz\db_rbac\behaviors\AccessBehavior;
use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\search\CategorySearch;
use yii\web\Controller;

/**
 * CategoryController реализует CRUD модели Category.
 */
class CategoryController extends Controller
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
                            'roles' => ['admin'],
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
}
