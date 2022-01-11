<?php

/**
 * Модуль API
 * Для подключения: добавить в 
 * $config = [ // ...
 *    'modules' => [
 *       'api' => [
 *           'basePath' => '@app/modules/api',
 *           'class' => 'app\modules\api\Module'
 *       ]
 *   ],
 * Использовать: описано в файлах контроллеров
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API
 */

namespace app\modules\api;

/**
 * Базовый класс модуля
 */
class Module extends \yii\base\Module {

    public function init() {
        parent::init();
    }

    /**
     * Действия перед запуском любого метода модуля
     * @param Action $action
     * @return boolean
     */
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }

}
