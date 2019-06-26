<?php

namespace App\Exceptions;

use App\Common\Util;
use App\Http\Response\JsonResponse;
use App\Model\Code;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //region 没有匹配到路由
        if ($exception instanceof NotFoundHttpException) {
            Code::setCode(Code::ERR_URL_ERROR);
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        //endregion
        //region 找不到模型
        if ($exception instanceof ModelNotFoundException) {
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
                //资源路由，id在地址参数里
                $ids = $request->route()->parameters();
                if (array_key_exists($baseModelName, $ids)) {
                    $ids = $ids[$baseModelName];
                } else {
                    $ids = array_values($ids);
                }
            }
            if (is_array($ids) && count($ids) === 1) {
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
        if ($exception instanceof InvalidParameterException) {
            Code::setCode($exception->getCode());
            return new JsonResponse(['detail' => $exception->errors()], Response::HTTP_BAD_REQUEST);
        }
        //endregion
        //region 校验失败
        if ($exception instanceof ValidationException) {
            //校验失败
            Code::setCode($exception->getCode(), $exception->getMessage());
            return new JsonResponse($exception, Response::HTTP_BAD_REQUEST);
        }
        //endregion
        //region CSRF校验失败
        if ($exception instanceof TokenMismatchException) {
            Code::setCode(Code::ERR_CSRF);
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        //endregion
        //region 在黑名单内
        if ($exception instanceof BlacklistException) {
            Code::setCode(Code::ERR_USER_BLACKLIST);
            return new JsonResponse();
        }
        //endregion
        //region 用户未登录
        if ($exception instanceof AuthenticationException) {
            Code::setCode(Code::ERR_NOT_LOGIN);
            return new JsonResponse();
        }
        //endregion
        //region 没有权限
        if ($exception instanceof AuthorizationException) {
            Code::setCode(Code::ERR_NO_PERMISSION);
            return new JsonResponse();
        }
        //endregion
        //region 数据库操作失败
        if ($exception instanceof QueryException) {
            Code::setCode(Code::ERR_DB_FAIL);
            return new JsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        //endregion
        Code::setCode(Code::ERR_FAIL);
        $message = trim($exception->getMessage());
        if ($message) {
            $message = ':' . $message;
        }
        $message = get_class($exception) . $message;
        return new JsonResponse([$message, $exception->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
