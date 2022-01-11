<?php

/**
 * ReferatController class file
 * @author cheva
 */

/**
 * ReferatController class
 * See protected/scripts folder with cli SOAP client examples
 * 
 * @todo add $theme as list to WSDL description plus validators for it
 * @todo use single WsdlRequest class in all methods and WSDL scheme description
 * @todo use single WsdlResponse class to return objects with errors
 */
class ReferatController extends CController
{

    /**
     *  @todo move constants to config
     */
    const VESNA_URL = 'https://vesna.yandex.ru/referats/write/?t=';

    /**
     * Default WSDL schema for Referat model and actions.
     * See /index.php?r=referat/wsdl
     * 
     * @return type
     */
    public function actions() {
        return array(
            'wsdl' => array(
                'class' => 'CWebServiceAction',
                'classMap' => array(
                    'Referat' => 'Referat',
                ),
            ),
        );
    }

    /**
     * Get full record list.
     * 
     * @return Referat[] records list
     * @soap
     */
    public function getList() {
        $response = Referat::model()->findAll();
        if (empty($response)) {
            throw new Exception('Nothing found', 404);
        }
        return $response;
    }

    /**
     * Get last inserted record.
     * 
     * @return Referat object last record
     * @soap
     */
    public function getLast() {
        $response = Referat::model()->lastRecord()->find();
        if (empty($response)) {
            throw new Exception('Nothing found', 404);
        }
        return $response;
    }

    /**
     * Get from vesna, insert and return new record.
     * Possible theme values: 'astronomy','geology','gyroscope','literature','marketing',
     *   'mathematics','music','polit','agrobiologia','law','psychology',
     *   'geography','physics','philosophy','philosophy','chemistry','estetica'
     * 
     * @param string theme
     * @return Referat object new record
     * @soap
     */
    public function getNew($theme) {

        if ($curl = curl_init()) {
            // get
            curl_setopt($curl, CURLOPT_URL, self::VESNA_URL . $theme);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($curl);
            curl_close($curl);
            // parse
            $referat = new Referat;
            $pattern = '/\<div\>(.*)\<\/div\>\<strong\>(.*)\<\/strong\>(.*)/i';
            $result = preg_match($pattern, $out, $matches);
            if ($result) {
                // insert (using Yii validators)
                $referat->theme = $theme;
                $referat->theme_alias = $matches[1];
                $referat->title = $matches[2];
                $referat->body = $matches[3];
                if ($referat->save()) {
                    return $referat;
                } else {
                    throw new Exception('Parser result save error', 500);
                }
            } else {
                throw new Exception('CURL result parse error', 500);
            }
        } else {
            throw new Exception('CURL init error', 500);
        }
        throw new Exception('Something wrong', 500);
    }

}
