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
        'default' => [
            'title'     => 'required|string|min:1',
            'content'   => 'required|string|min:1',
            'cover_img' => 'filled|url',
            'tags'      => 'filled|array',
            'hide'      => 'boolean',
        ],
        'index'   => [
            'title'     => '',
            'content'   => '',
            'cover_img' => '',
            'tags'      => '',
            'hide'      => 'boolean',
        ],
    ];

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        $this->checkValidate($request->all(), 'index');
        $pagination = Article::orderBy('updated_at', 'DESC')->paginate($this->pageSize);
        return $this->response($this->getPaginateResponse($pagination));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): Response
    {
        return $this->response(new Article($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request): Response
    {
        $this->checkValidate($request->all(), 'store');
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
        $article->saveOrFail();
        return $this->response($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article): Response
    {
        return $this->response($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Article                  $article
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, Article $article): Response
    {
        $this->checkValidate($request->all(), 'update');
        ($coverImg = $request->input('cover_img')) !== null && $article->cover_img = $coverImg;
        ($title = $request->input('title')) !== null && $article->title = $title;
        ($content = $request->input('content')) !== null && $article->content = $content;
        ($tags = $request->input('tags')) !== null && $article->tags = $tags;
        ($hide = $request->input('hide')) !== null && $article->hide = $hide;
        $article->ip  = Util::getUserIp($request);
        $article->uid = Auth::id();
        $article->saveOrFail();
        return $this->response($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article): Response
    {
        $row = $article->delete();
        if (!$row) {
            Code::setCode(Code::ERR_DB_FAIL);
        }
        return $this->response();
    }
}
