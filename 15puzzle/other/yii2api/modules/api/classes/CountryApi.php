<?php

/**
 * Файл класса CountryApi
 * Использование JSON: http://logos.dev/web/index.php?r=api/json&q={"appKey":"internal-app-key","apiKey":"e5a303bc-ec5c-40b5-b504-1bcbfdd29f87","modelName":"currency","calledMethod":"all"} 
 * Или XML: http://logos.dev/web/index.php?r=api/xml&q=<?xml%20version="1.0"%20encoding="UTF-8"%20?><root><appKey>internal-app-key</appKey><apiKey>e5a303bc-ec5c-40b5-b504-1bcbfdd29f87</apiKey><modelName>country</modelName><calledMethod>view</calledMethod><methodProperties><id>616</id></methodProperties></root>
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/classes
 */

namespace app\modules\api\classes;

use app\models\country\Country;
use app\modules\api\classes\base\BaseApiResponse;

/**
 * CountryApi класс
 * Содержит методы получения и обработки данных модели Country
 * Используется API контроллерами
 */
class CountryApi {

    /**
     * Получение одной страны по id
     * @param array $params параметры запроса
     * $params[id] обязательно!
     * @return BaseApiResponse
     */
    public function view($params) {
        
        $response = new BaseApiResponse;
        
        if (empty($params['id'])) {
            $response->errors[] = 'Id обязателен!';
            return $response;
        }
        $result = Country::findOne($params['id']);
        if (empty($result)) {
            $response->errors[] = 'Ничего не найдено!';
        } else {
            $response->data = $result->getAttributes();
            $response->success = 'true';
            $response->info = 'Найдена 1 запись.';
        }
        return $response;
    }

}
