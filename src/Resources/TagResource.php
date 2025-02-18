<?php

namespace Firefly\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Firefly\FilamentBlog\Models\Tag;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string {
        return trans('filament-blog::cafali-blog.tags.title_page');
    }

    public static function getLabel(): string {
        return trans('filament-blog::cafali-blog.tags.title_page');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Tag::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-blog::cafali-blog.tags.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(trans('filament-blog::cafali-blog.tags.slug')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(trans('filament-blog::cafali-blog.edit')),
                Tables\Actions\ViewAction::make()
                    ->label(trans('filament-blog::cafali-blog.view')),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Firefly\FilamentBlog\Resources\TagResource\Pages\ListTags::route('/'),
            'edit' => \Firefly\FilamentBlog\Resources\TagResource\Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
