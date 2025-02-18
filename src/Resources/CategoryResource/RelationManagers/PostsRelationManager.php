<?php

namespace Firefly\FilamentBlog\Resources\CategoryResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Firefly\FilamentBlog\Models\Post;
use Illuminate\Support\Str;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(Post::getForm());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(trans('filament-blog::cafali-blog.posts.title'))
                    ->limit(40)
                    ->description(function (Post $record) {
                        return Str::limit($record->sub_title);
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label(trans('filament-blog::cafali-blog.posts.status'))
                    ->badge()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(trans('filament-blog::cafali-blog.create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(trans('filament-blog::cafali-blog.edit'))
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->label(trans('filament-blog::cafali-blog.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(trans('filament-blog::cafali-blog.delete')),
                ]),
            ]);
    }
}
