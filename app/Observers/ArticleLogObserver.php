<?php

namespace App\Observers;

use App\Model\Article;
use Illuminate\Support\Arr;

/**
 * 文章相关操作日志
 * Class ArticleLogObserver
 *
 * @package App\Observers
 */
class ArticleLogObserver extends CommonLogBaseObserver
{
    protected static $methodTitles = [
        'created' => '发表',
    ];

    protected static $extraFields = [
        'id'    => 'ID',
        'title' => '标题',
    ];

    public function created(Article $article): bool
    {
        return $this->__call(__FUNCTION__, Arr::wrap($article));
    }

    public function updated(Article $article): bool
    {
        return $this->__call(__FUNCTION__, Arr::wrap($article));
    }

    public function deleted(Article $article): bool
    {
        return $this->__call(__FUNCTION__, Arr::wrap($article));
    }
}
