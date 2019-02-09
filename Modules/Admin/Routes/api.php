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

//Route::middleware('auth:token')->get('/web', function (Request $request) {
//    return $request->user();
//});


Illuminate\Support\Facades\Route::prefix('/admin')->group(function (\Illuminate\Routing\Router $router) {
    $router->pattern('article', '\\d+');
    $router->apiResource('article/article', 'Article\\ArticleController');
    $router->pattern('comment', '\\d+');
    $router->apiResource('article/comment', 'Article\\CommentController');
    $router->pattern('user', '\\d+');
    $router->pattern('log', '\\d+');
    $router->apiResource('user/log', 'User\\UserLogController', [
        'only' => ['index', 'show'],
    ]);

    $router->any('{module}/{controller}/{action?}', function (string $module, string $controller, string $action = 'index') {
        $method     = strtolower(request()->method());
        $module     = ucfirst($module);
        $action     = $method . ucfirst($action);
        $controller = ucfirst($controller) . 'Controller';

        $namespaces = [
            'Modules',
            'Admin',
            'Http',
            'Controllers',
            $module,
            $controller,
        ];
        $className  = implode('\\', $namespaces);
        //region 检查控制器与方法是否存在
        if (!class_exists($className)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($className);
        }
        $clazz = new ReflectionClass($className);
        if (!$clazz->hasMethod($action)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($className . ':' . $action);
        }
        //endregion
        /** @var \App\Http\Controllers\AppController $class */
        $class = new $className();
        return $class->callAction($action, [request(),]);
    });
});
