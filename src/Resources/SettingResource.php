<?php

namespace Firefly\FilamentBlog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Firefly\FilamentBlog\Models\Setting;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 8;

    public static function getNavigationLabel(): string {
        return trans('filament-blog::filament-blog.settings.title_page');
    }

    public static function getLabel(): string {
        return trans('filament-blog::filament-blog.settings.title_page');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Setting::getForm());
    }

    public static function canCreate(): bool
    {
        return Setting::count() === 0;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(25)
                    ->label(trans('filament-blog::filament-blog.settings.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->label(trans('filament-blog::filament-blog.settings.description'))
                    ->searchable(),

                Tables\Columns\ImageColumn::make('logo'),

                Tables\Columns\TextColumn::make('organization_name')
                    ->label(trans('filament-blog::filament-blog.settings.organization')),

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
                    ->label(trans('filament-blog::filament-blog.edit')),
                Tables\Actions\ViewAction::make()
                    ->label(trans('filament-blog::filament-blog.view')),
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
            'index' => \Firefly\FilamentBlog\Resources\SettingResource\Pages\ListSettings::route('/'),
            'create' => \Firefly\FilamentBlog\Resources\SettingResource\Pages\CreateSetting::route('/create'),
            'edit' => \Firefly\FilamentBlog\Resources\SettingResource\Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
