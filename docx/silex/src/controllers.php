<?php

/**
 * File for silex main controller
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DocxController;

// Mount main controller
$app->mount('/', new DocxController());

/**
 * process standart errors
 * @param \Exception $e, 
 * @param Request $request
 * @param int $code 
 * @use $app
 * @return Response
 */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html',
        'errors/' . substr($code, 0, 2) . 'x.html',
        'errors/' . substr($code, 0, 1) . 'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

/**
 * HTTP basic authentication
 * @param Silex\Application $app
 * @return json
 */
$app->before(function() use ($app)
{
    if (!isset($_SERVER['PHP_AUTH_USER']))
    {
        header('WWW-Authenticate: Basic realm="docx"');
        return $app->json(array('Message' => 'Not Authorised'), 401);
    }
    else
    {
        //once the user has provided some details, check them
        $users = array(
            'docx' => 'dcxpsswd'
            );

        if($users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW'])
        {
            //If the password for this user is not correct then resond as such
            return $app->json(array('Message' => 'Forbidden'), 403);
        }

        //If everything is fine then the application will carry on as normal
    }
});