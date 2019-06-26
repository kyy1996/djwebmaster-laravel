<?php

namespace Modules\Admin\Http\Controllers\Article;

use App\Common\Util;
use App\Model\Article;
use App\Model\Code;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Controllers\AdminController;

/**
 * 文章资源控制器
 * Class ArticleController
 *
 * @package Modules\Admin\Http\Controllers\Article
 */
class ArticleController extends AdminController
{
    protected static $rules = [
        'default'      => [
            'title'     => 'required|string|min:1',
            'content'   => 'required|string|min:1',
            'cover_img' => [
                'nullable',
                'regex:/^(http:|https:)?\/\/[^:\s]+$/',
            ],
            'tags'      => 'nullable|array',
            'hide'      => 'boolean',
            'id'        => 'nullable|integer|min:0',
        ],
        'getIndex'     => [
            'title'     => '',
            'content'   => '',
            'cover_img' => '',
            'tags'      => '',
        ],
        'deleteDelete' => [
            'title'     => '',
            'content'   => '',
            'cover_img' => '',
            'tags'      => '',
            'id'        => 'required|integer|min:1',
        ],
        'getShow'      => [
            'title'     => '',
            'content'   => '',
            'cover_img' => '',
            'tags'      => '',
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $pagination = Article::orderBy('updated_at', 'DESC')->paginate($this->pageSize);
        return $this->response($this->getPaginateResponse($pagination));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postUpdate(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $article            = (($id = $request->input('id')) ?: 0) > 0 ? Article::findOrFail($id) : new Article();
        $article->title     = $request->input('title') ?: '';
        $article->content   = $request->input('content') ?: '';
        $article->cover_img = $request->input('cover_img') ?: '';
        $article->tags      = array_values(array_filter((array)$request->input('tags'), 'trim'));
        $article->hide      = (bool)$request->input('hide');
        $article->ip        = Util::getUserIp($request);
        $article->uid       = Auth::id();
        $article->extra     = $request->input('extra') ?: '{}';
        $article->saveOrFail();
        $article->load('user');
        return $this->response($article);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        if (($id = +$request->input('id', 0)) > 0) {
            $article = Article::findOrFail($id);
        } else {
            $article = new Article();
        }
        return $this->response($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function deleteDelete(Request $request): Response
    {
        $this->checkValidate($request->all(), __FUNCTION__);
        $article = Article::findOrFail($request->input('id'));
        $row     = $article->delete();
        if (!$row) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response();
    }
}
