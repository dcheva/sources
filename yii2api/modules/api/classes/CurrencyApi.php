<?php

/**
 * Файл класса CurrencyApi
 * Использование JSON: http://logos.dev/web/index.php?r=api/json&q={"appKey":"internal-app-key",apiKey":"e5a303bc-ec5c-40b5-b504-1bcbfdd29f87","modelName":"currency","calledMethod":"all"}
 * Или XML: http://logos.dev/web/index.php?r=api/xml&q=<?xml version="1.0" encoding="UTF-8" ?><root><appKey>internal-app-key</appKey><apiKey>e5a303bc-ec5c-40b5-b504-1bcbfdd29f87</apiKey><modelName>currency</modelName><calledMethod>all</calledMethod></root>
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/classes
 */

namespace app\modules\api\classes;

use app\models\currency\Currency;
use app\modules\api\classes\base\BaseApiResponse;

/**
 * CurrencyApi класс
 * Содержит методы получения и обработки данных модели Currency
 * Используется API контроллерами
 */
class CurrencyApi {

    /**
     * Получение всего списка валют
     * @return BaseApiResponse
     */
    public function all() {
        
        $response = new BaseApiResponse;
        
        $result = Currency::find()->all();
        if (empty($result)) {
           $response->errors[] = 'Ничего не найдено!';
           return $response;
        }
        foreach($result as $o_currency) {
            $response->data[] = $o_currency->getAttributes();
        }
        $response->info = ['Получено ' . count($result) . ' записей.'];
        return $response;
    }


}
