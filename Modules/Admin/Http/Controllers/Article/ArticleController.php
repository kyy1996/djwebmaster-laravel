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
            'cover_img' => 'filled|url',
            'tags'      => 'filled|array',
            'hide'      => 'boolean',
            'id'        => 'integer|min:1',
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
        $this->checkValidate($request->all(), 'getIndex');
        $pagination = Article::orderBy('updated_at', 'DESC')->paginate($this->pageSize);
        return $this->response($this->getPaginateResponse($pagination));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postUpdate(Request $request): Response
    {
        $this->checkValidate($request->all(), 'postUpdate');
        if (($id = $request->input('id', 0)) > 0) {
            $article = Article::findOrFail($id);
            ($coverImg = $request->input('cover_img')) !== null && $article->cover_img = $coverImg;
            ($title = $request->input('title')) !== null && $article->title = $title;
            ($content = $request->input('content')) !== null && $article->content = $content;
            ($tags = $request->input('tags')) !== null && $article->tags = $tags;
            ($hide = $request->input('hide')) !== null && $article->hide = $hide;
            $article->ip  = Util::getUserIp($request);
            $article->uid = Auth::id();
        } else {
            $data    = [
                'uid'           => Auth::id(),
                'title'         => $request->input('title'),
                'content'       => $request->input('content'),
                'cover_img'     => $request->input('cover_img', ''),
                'tags'          => $request->input('tags', []),
                'hide'          => $request->input('hide', 0),
                'read_count'    => 0,
                'comment_count' => 0,
                'extra'         => [],
                'ip'            => Util::getUserIp($request),
            ];
            $article = new Article($data);
        }
        $article->saveOrFail();
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
        $this->checkValidate($request->all(), 'getShow');
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
        $this->checkValidate($request->all(), 'deleteDelete');
        $article = Article::findOrFail($request->input('id'));
        $row     = $article->delete();
        if (!$row) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response();
    }
}
