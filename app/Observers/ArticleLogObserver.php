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
    protected static $messages = [
        'created' => '发表',
    ];

    public function created(Article $article)
    {
        return $this->__call('created', Arr::wrap($article));
    }

    public function updated(Article $article)
    {
        return $this->__call('updated', Arr::wrap($article));
    }

    public function deleted(Article $article)
    {
        return $this->__call('deleted', Arr::wrap($article));
    }

    public function retrieved(Article $article)
    {
        return $this->__call('retrieved', Arr::wrap($article));
    }
}
