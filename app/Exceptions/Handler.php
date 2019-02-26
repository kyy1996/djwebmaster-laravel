<?php

namespace App\Exceptions;

use App\Common\Util;
use App\Http\Response\JsonResponse;
use App\Model\Code;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //region 没有匹配到路由
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            Code::setCode(Code::ERR_URL_ERROR);
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        //endregion
        //region 找不到模型
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            //找不到模型
            //region 解析模型名称
            $model         = $exception->getModel();
            $pos           = strrpos($model, '\\');
            $baseModelName = strtolower(substr($model, $pos ? $pos + 1 : 0));
            $primaryKey    = 'id';
            if (class_exists($model)) {
                $model = new $model();
                if (property_exists($model, 'modelName')) {
                    $modelName = $model->modelName;
                } else {
                    $modelName = $baseModelName;
                }
                if (method_exists($model, 'getRouteKeyName')) {
                    $primaryKey = $model->getRouteKeyName() ?: $primaryKey;
                } else if (method_exists($model, 'getPrimaryKey')) {
                    $primaryKey = $model->getPrimaryKey() ?: $primaryKey;
                } else if (property_exists($model, 'primaryKey')) {
                    $primaryKey = $model->primaryKey ?: $primaryKey;
                }
            } else {
                $modelName = $model;
            }
            //endregion
            //region 解析要获取的模型ID号
            $ids = $exception->getIds();
            if (!$ids) {
                $ids = $request->route()->parameters();
                if (key_exists($baseModelName, $ids)) {
                    $ids = $ids[$baseModelName];
                } else {
                    $ids = array_values($ids);
                }
            }
            if (is_array($ids) && count($ids) == 1) {
                $ids = array_pop($ids);
            }
            if (is_numeric($ids)) {
                $ids = +$ids;
            }
            //endregion
            Code::setCode(Code::ERR_MODEL_NOT_FOUND, '没有找到' . $primaryKey . '为' . Util::toJson($ids) . '的' . $modelName);
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        //endregion
        //region 参数错误
        if ($exception instanceof \Symfony\Component\Routing\Exception\InvalidParameterException) {
            Code::setCode($exception->getCode());
            return new JsonResponse(['detail' => Util::fromJson($exception->getMessage())], Response::HTTP_BAD_REQUEST);
        }
        //endregion
        //region 校验失败
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            //校验失败
            Code::setCode($exception->getCode(), $exception->getMessage());
            return new JsonResponse($exception, Response::HTTP_BAD_REQUEST);
        }
        //endregion
        //region 数据库操作失败
        if ($exception instanceof \Illuminate\Database\QueryException) {
            Code::setCode(Code::ERR_DB_FAIL);
            return new JsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        //endregion
        Code::setCode(Code::ERR_FAIL);
        return new JsonResponse([$exception->getMessage(), $exception->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
