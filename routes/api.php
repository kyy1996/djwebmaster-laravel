<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('article/index', 'Article\\ArticleController@getIndex');

Route::any('{module}/{controller}/{action}', function (string $module, string $controller, string $action) {
    $method     = strtolower(request()->method());
    $module     = ucfirst($module);
    $action     = $method . ucfirst($action);
    $controller = ucfirst($controller) . 'Controller';

    $namespaces = [
        'App',
        'Http',
        'Controllers',
        $module,
        $controller,
    ];
    $className  = implode('\\', $namespaces);
    if (!class_exists($className)) {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($className);
    }
    $class = new $className();
    return $class->callAction($action, [request(),]);
});
