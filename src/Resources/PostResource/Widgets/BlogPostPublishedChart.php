<?php

namespace Firefly\FilamentBlog\Resources\PostResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Firefly\FilamentBlog\Models\Post;

class BlogPostPublishedChart extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make('Published Post', Post::published()->count())
            ->label(trans('filament-blog::filament-blog.posts.published')),
            BaseWidget\Stat::make('Scheduled Post', Post::scheduled()->count())
                ->label(trans('filament-blog::filament-blog.posts.scheduled')),
            BaseWidget\Stat::make('Pending Post', Post::pending()->count())
                ->label(trans('filament-blog::filament-blog.posts.pending')),
        ];
    }
}
