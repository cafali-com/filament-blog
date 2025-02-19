<?php

namespace Firefly\FilamentBlog\Resources\PostResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Firefly\FilamentBlog\Models\SeoDetail;

class SeoDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'seoDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema(SeoDetail::getForm());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(trans('filament-blog::filament-blog.seo.title')),
                Tables\Columns\TextColumn::make('description')
                    ->label(trans('filament-blog::filament-blog.seo.description')),
                Tables\Columns\TextColumn::make('keywords')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(trans('filament-blog::filament-blog.seo.add_seo')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(trans('filament-blog::filament-blog.edit'))
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->label(trans('filament-blog::filament-blog.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(trans('filament-blog::filament-blog.delete')),
                ]),
            ]);
    }
}
