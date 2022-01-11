<?php

class StockController extends CController
{

    public function actions() {
        return array(
            'quote' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }

    /**
     * @param string индекс предприятия
     * @return float цена
     * @soap
     */
    public function getPrice($symbol) {
        $prices = array('IBM' => 100, 'GOOGLE' => 350);
        return isset($prices[$symbol]) ? $prices[$symbol] : 0;
    }

}
