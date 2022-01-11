<?php

/**
 * Файл базового класса BaseApiController
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/classes/base
 */

namespace app\modules\api\classes\base;

use app\models\counterparty\CounterpartyApi;
use app\modules\api\classes\base\BaseApiResponse;

/**
 * BaseApiController класс
 * Наследуется классами XmlController и JsonController
 * Содержит основные параметры, метод установки параметров и 
 * фабрику для вызова классов (CurrencyApi итд) на основании параметров запроса
 */
class BaseApiController extends BaseApiRequest{

    /**
     * @var Counterparty запрашивающий Контрагент
     */
    public $counterparty;

    /**
     * @var app\modules\api\classes\base\BaseApiResponse обьект ответа сервера 
     */
    public $response;

    /**
     * Установка параметров после обработки конструктором контроллера
     * Используется в дочерних классах JsonController и XmlController
     * @param array $params параметры после распаковки
     * @throws \yii\web\BadRequestHttpException
     */
    public function setParams($params) {

        if (empty($this->response->errors)) {

            // Проверяем ключи
            $this->apiKey = $params->apiKey;
            $this->appKey = $params->appKey;
            
            if (empty($this->appKey) || empty($this->apiKey)) {
                $this->response->errors[] = 'apiKey и appKey обязательны!';
            } else {
                if (!in_array($this->appKey, \Yii::$app->params['app_keys'])) {
                    $this->response->errors[] = 'appKey не найден!';
                }
                $this->counterparty = CounterpartyApi::findOne(['api_key' => $this->apiKey]);
                if (!$this->counterparty) {
                    $this->response->errors[] = 'apiKey не найден!';
                }

                // Устанавливаем параметры
                $this->modelName = 'app\\modules\\api\\classes\\'
                        . ucfirst($params->modelName)
                        . "Api";
                $this->calledMethod = (string) $params->calledMethod;
                $this->methodParams = (array) $params->methodProperties;
                if (empty($this->modelName) || empty($this->calledMethod)) {
                    $this->response->errors[] = 'modelName и calledMethod обязательны!';
                }
            }
        }
        return;
    }

    /**
     * Фабрика для вызова $controller->$method($params)
     * Проверяет наличие класса и метода, вызывает и возвращает результат работы класса
     * @return array массив данных, полученных от вызываемого метода
     * @throws \yii\web\BadRequestHttpException
     */
    public function getData() {

        $cpath = $this->modelName;
        $method = $this->calledMethod;
        if (!empty($this->methodParams)) {
            $params = $this->methodParams;
        } else {
            $params = [];
        }
        if (!class_exists($cpath)) {
            $this->response->errors[] = 'Неправильно указан modelName!';
            return;
        }
        $controller = @new $cpath;
        if (!method_exists($controller, $method)) {
            $this->response->errors[] = 'Неправильно указан calledMethod!';
            return;
        }
        $this->response = $controller->$method($params);
        return;
    }

}
