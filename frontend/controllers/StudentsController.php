<?php

namespace frontend\controllers;

use backend\models\Students;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\components\ApiDataProvider;
use Yii;
use yii\helpers\Json;
use yii\caching\TagDependency;
/**
 * StudentsController implements the CRUD actions for Students model.
 */
class StudentsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Students models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ApiDataProvider([
            'apiUrl' => Yii::$app->httpClient->baseUrl . '/students',
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Students model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Students model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Students();

        if ($this->request->isPost) {
            $data = $this->request->post();
            $data = Json::encode($data['Students']);
            $response = Yii::$app->httpClient->post("students", $data, [
                'Content-Type' => 'application/json'
            ])->send();
            if($response->getIsOk()) {
                Yii::$app->session->setFlash('success', 'Created successfully');
                $data = $response->content;
                $data = Json::decode($data);
                TagDependency::invalidate(Yii::$app->cache, 'students-all');
                return $this->redirect(['view','id'=> $data['id']]);
            } else {
                $statusCode = $response->getStatusCode();
                Yii::$app->session->setFlash('error', "Error $statusCode");
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Students model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $data = $this->request->post();
            $data = Json::encode($data['Students']);
            $response = Yii::$app->httpClient->put("students/$id", $data, [
                'Content-Type' => 'application/json'
            ])->send();
            if($response->getIsOk()) {
                Yii::$app->session->setFlash('success', 'Updated successfully');
                $data = $response->content;
                $data = Json::decode($data);
                Yii::$app->cache->delete(`student-`.$id);
                TagDependency::invalidate(Yii::$app->cache, 'students-all');
                return $this->redirect(['view','id'=> $data['id']]);
            } else {
                $statusCode = $response->getStatusCode();
                Yii::$app->session->setFlash('error', "Error $statusCode");
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Students model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $response = Yii::$app->httpClient->delete("students/$id")->send();
        if($response->getIsOk()) {
            Yii::$app->session->setFlash("success","Deleted successfully");
            Yii::$app->cache->delete(`student-`.$id);
            TagDependency::invalidate(Yii::$app->cache, 'students-all');
            return $this->redirect(["index"]);
        } else {
            $statusCode = $response->getStatusCode();
            Yii::$app->session->setFlash("error", "Error $statusCode");
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Students model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Students the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $cache = Yii::$app->cache;
        $model = $cache->getOrSet(`student-`.$id , function () use ($id) {
            $response = Yii::$app->httpClient->get("students/$id")->send();
            if($response->getIsOk()) {
                $data = $response->data; 
                $model = new Students();
                $model->attributes = $data; 
                return $model;
            }
            throw new NotFoundHttpException('The requested page does not exist.');
        });
        return $model;
    }
}
