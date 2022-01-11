<?php

/*
 * Файл базового класса BaseApiResponse
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/classes/base
 */

namespace app\modules\api\classes\base;

/**
 * BaseApiResponse класс
 * Используется как шаблон данных для формирования ответа
 */
class BaseApiResponse {

    /**
     * Статус операции (string true|false для генератора xml обязательно!)
     * @var string
     */
    public $success = 'false';
    /**
     * Данные ответа сервера
     * @var array 
     */
    public $data = [];
    /**
     * Ошибки
     * @var array 
     */
    public $errors = [];
    /**
     * Замечания
     * @var array 
     */
    public $warnings = [];
    /**
     * Информация о выполненной операции
     * @var array 
     */
    public $info = [];

}
