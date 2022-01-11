<?php

/**
 * Файл контроллера SOAP
 * Использование: запрос из SOAP клиента (смотри скрипты в папке ../cli/)
 * WSDL Схема: http://logos.dev/web/index.php?r=api/soap/get
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/controllers
 */

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;

/**
 * SoapController класс
 * Использует вендор mongosoft\soapserver
 */
class SoapController extends Controller {

    /**
     * actions with soapserver proxy class
     * @return array actions
     */
    public function actions() {
        return [
            'get' => [
                'class' => 'mongosoft\soapserver\Action',
            ],
        ];
    }

    /**
     * Получение обьекта страны по id
     * @request integer $id ПК страны
     * @return array аттрибуты обьекта страны
     * @soap
     */
    public function getCountry($id) {
        $response = \app\models\country\Country::findOne($id)->getAttributes();
        return $response;
    }

}
