<?php

namespace Firefly\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Firefly\FilamentBlog\Models\SeoDetail;

class SeoDetailResource extends Resource
{
    protected static ?string $model = SeoDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string {
        return trans('filament-blog::filament-blog.seo.title_page');
    }

    public static function getLabel(): string {
        return trans('filament-blog::filament-blog.seo.title_page');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(SeoDetail::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('post.title')
                    ->label(trans('filament-blog::filament-blog.seo.posttitle'))
                    ->limit(20),
                Tables\Columns\TextColumn::make('title')
                    ->label(trans('filament-blog::filament-blog.seo.title'))
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('keywords')->badge()
                    ->label(trans('filament-blog::filament-blog.seo.keywords'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(trans('filament-blog::filament-blog.edit')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label(trans('filament-blog::filament-blog.delete')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Firefly\FilamentBlog\Resources\SeoDetailResource\Pages\ListSeoDetails::route('/'),
            'create' => \Firefly\FilamentBlog\Resources\SeoDetailResource\Pages\CreateSeoDetail::route('/create'),
            'edit' => \Firefly\FilamentBlog\Resources\SeoDetailResource\Pages\EditSeoDetail::route('/{record}/edit'),
        ];
    }
}
