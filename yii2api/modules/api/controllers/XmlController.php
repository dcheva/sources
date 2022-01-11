<?php

/**
 * Файл класса контроллера XML API
 * Использование: http://logos.dev/web/index.php?r=api/xml&q=<?xml version="1.0" encoding="UTF-8" ?><request><apiKey>e5a303bc-ec5c-40b5-b504-1bcbfdd29f87</apiKey><modelName>country</modelName><calledMethod>view</calledMethod><methodProperties><id>616</id></methodProperties></request>
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/controllers
 */

namespace app\modules\api\controllers;

use app\modules\api\classes\base\BaseApiController;
use app\modules\api\classes\base\BaseApiResponse;

/**
 * XmlController класс
 * Точка входа api/xml
 * Содержит распаковщик строки запроса и запаковщик ответа
 */
class XmlController extends BaseApiController {

    /**
     * Конструктор класса
     * Распаковывает XML запрос
     * Использует метод setParams родительского класса 
     * для установки параметров родительского класса
     * @param string $id Идентификатор модуля
     * @param Module $module Модуль, которому принадлежит конструктор
     * @param arrat $config Пары Имя-Значение для конфигурации модуля
     * @throws \yii\web\BadRequestHttpException
     */
    public function __construct($id, $module, $config = array()) {

        $this->response = new BaseApiResponse;
        
        $query = urldecode(\Yii::$app->request->get('q'));
        $params = @simplexml_load_string($query);
        if (empty($params)) {
            $this->response->errors[] = 'Пустой запрос';
        }
        $this->setParams($params);
        parent::__construct($id, $module, $config);
    }

    /**
     * Осноовной метод класса
     * Получает данные из метода getData родительского класса и пакует в XML
     * @return string xml результат обработки запроса
     */
    public function actionIndex() {
        if(empty($this->response->errors)) {
            $this->getData();
        }
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><root></root>");
        $response = $this->array_to_xml($this->response, $xml);
        return $response->asXML();
    }

    /**
     * Стандартный метод преобразования массива в SimpleXML
     * @param array $result
     * @param SimpleXMLElement $response
     * @return SimpleXMLElement
     */
    private function array_to_xml($result, $response) {
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $response->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                } else {
                    $subnode = $response->addChild("item_$key");
                    $this->array_to_xml($value, $subnode);
                }
            } else {
                $response->addChild("$key", htmlspecialchars("$value"));
            }
        }
        return $response;
    }

}
