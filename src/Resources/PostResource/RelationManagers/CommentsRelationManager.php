<?php

namespace Firefly\FilamentBlog\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('comment')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('comment')
                    ->label(trans('filament-blog::cafali-blog.comments.comment'))
                    ->limit(20),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(trans('filament-blog::cafali-blog.comments.username')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(trans('filament-blog::cafali-blog.comments.add_comment')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(trans('filament-blog::cafali-blog.edit')),
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
