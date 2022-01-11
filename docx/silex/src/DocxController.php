<?php

/**
 * File for DocxController class
 */
require_once 'Cached.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Api\ControllerProviderInterface;
use Cached;

/**
 * DocxController (mouned on '/')
 */
class DocxController implements ControllerProviderInterface
{

    /**
     * Bind query methods to controllers
     * @param Silex\Application $app
     * @return \Silex\Application
     */
    public function connect(Silex\Application $app)
    {

        $controllers = $app['controllers_factory'];

        $controllers->post('/', array($this, 'createFromTemplate'))
                ->bind('createFromTemplate');

        return $controllers;
    }

    /**
     * Generate DocX file from template and variables
     * @param Request $request
     * @param Silex\Application $app
     * @return json
     */
    public function createFromTemplate(Request $request, Silex\Application $app)
    {
        $url = $request->get('url');
        $data = $request->get('data');

        if (!(substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://')) {
            return $app->json(array('Message' => "Url must be http:// or https:// ($url)"), 500);
        }
        if (!(substr($url, -5, 5) == '.docx' || substr($url, -5, 0) == '.DOCX')) {
            return $app->json(array('Message' => "File must be .docx ($url)"), 500);
        }
        if (empty(json_decode($request->get('data')))) {
            return $app->json(array('Message' => "Data must be Json single array ($data)"), 500);
        }

        $template = file_get_contents($url);
        $storage = __DIR__ . "/../web/storage/";
        $output_file = $storage . microtime(true);
        $output_url = 'http://' . $_SERVER["SERVER_NAME"]
                . '/storage/' . basename($output_file);

        if (empty($template)) {
            // catch error
            return $app->json(array('Message' => "Empty file given ($url)"), 500);
        } else {
            $template_name = $storage . basename($url);
            $res = file_put_contents($template_name, $template);
            if (!$res) {
                // catch error
                return $app->json(array('Message' => "Template save error"), 500);
            }
            $a_data = [
                'tpl' => $template_name,
                'data' => $data
            ];

            // execute phpdocx, store data in cache
            $cached = new Cached;
            $cached->setCached(md5($output_file), serialize($a_data));
            $cmd = 'php ' . __DIR__ . '/PhpDocx.php ' . $output_file;
            exec($cmd, $res);
            $result = 'Result: ' . implode(PHP_EOL, $res);

            if (!empty($res)) {
                // catch error
                return $app->json(array('Message' => $result), 500);
            }
            return $app->json(array(
                        'Message' => 'ok',
                        'url' => $output_url . '.docx'
                            ), 200);
        }
    }

}
