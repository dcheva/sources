<?php

/*
 * Файл базового класса BaseApiRequestInterface
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/classes/base
 */

namespace app\modules\api\classes\base;
use yii\base\Controller;

/**
 * BaseApiRequest класс
 * Используется как шаблон данных запроса
 */
class BaseApiRequest extends Controller {

    /**
     * @var string API key из запроса
     */
    public $apiKey;

    /**
     * @var string APP key из запроса
     */
    public $appKey;

    /**
     * @var string model имя класса контроллера
     */
    public $modelName;

    /**
     * @var string string имя класса метода
     */
    public $calledMethod;

    /**
     * @var array параметры вызова метода 
     */
    public $methodParams;

}
