<?php

namespace Modules\Admin\Http\Controllers\Article;

use App\Model\Article;
use App\Model\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Http\Controllers\AdminController;

class CommentController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\Article $article
     * @param  Comment           $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article, Comment $comment): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Comment                  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment): Response
    {
        //
    }
}
