<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ArticleRepositoryInterface;
use App\Models\Article;

class ArticleRepository extends SingleKeyModelRepository implements ArticleRepositoryInterface
{
    protected $querySearchTargets = ['slug', 'title', 'keywords', 'description', 'content'];

    public function getBlankModel()
    {
        return new Article();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function findBySlug($slug)
    {
        return Article::whereSlug($slug)->first();
    }
}
