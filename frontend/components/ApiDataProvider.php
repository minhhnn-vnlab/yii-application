<?php
namespace frontend\components;

use yii\data\BaseDataProvider;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use Yii;
use backend\models\Students;
use yii\helpers\Json;
use yii\caching\TagDependency;

class ApiDataProvider extends BaseDataProvider
{
    /**
     * @var string URL của API để lấy dữ liệu
     */
    public $apiUrl;

    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = [];
        if ($this->apiUrl === null) {
            throw new InvalidConfigException('The "apiUrl" property must be set.');
        }
        $apiUrl = $this->apiUrl;

        $models = Yii::$app->cache->getOrSet("students", function () use ($apiUrl) {
            $response = Yii::$app->httpClient->get($this->apiUrl)->send();

            if ($response->getIsOk()) {
                $data = Json::decode($response->content, true);
                $models = array_map(function($model) {
                    $student = new Students();
                    $student->attributes = $model;
                    return $student;
                }, $data);

                return $models;
            } else {
                throw new \Exception('Failed to fetch data from API.');
            }
        }, null, new TagDependency(['tags' => 'students-all']));
        if ($models === null) {
            throw new \Exception('Failed to retrieve models from cache or API.');
        }

        $pagination = $this->getPagination();
        if ($pagination !== false) {
            $pagination->totalCount = count($models);
            $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit());
        }
        return $models;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareKeys($models)
    {
        $keys = [];
        foreach ($models as $model) {
            if (isset($model["id"])) {
                $keys[] = $model["id"];
            } else {
                Yii::warning('Model does not have an ID: ' . Json::encode($model), __METHOD__);
            }
        }
        return $keys;
    }


    /**
     * {@inheritdoc}
     */
    protected function prepareTotalCount()
    {
        // Chỉ tính toán tổng số bản ghi
        if ($this->apiUrl === null) {
            throw new InvalidConfigException('The "apiUrl" property must be set.');
        }

        $response = Yii::$app->httpClient->get($this->apiUrl)->send();

        return $response->getIsOk() ? count($response->data) : 0;
    }
}
