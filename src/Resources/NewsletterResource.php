<?php

namespace Firefly\FilamentBlog\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Firefly\FilamentBlog\Models\NewsLetter;

class NewsletterResource extends Resource
{
    protected static ?string $model = NewsLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string {
        return trans('filament-blog::cafali-blog.newsletters.title_page');
    }

    public static function getLabel(): string {
        return trans('filament-blog::cafali-blog.newsletters.title_page');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label(trans('filament-blog::cafali-blog.newsletters.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
                Forms\Components\Toggle::make('subscribed')
                    ->label(trans('filament-blog::cafali-blog.newsletters.subscribed'))
                    ->default(true)
                    ->required()->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('subscribed')
                    ->label('Subscribed'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => \Firefly\FilamentBlog\Resources\NewsletterResource\Pages\ListNewsletters::route('/'),
            'create' => \Firefly\FilamentBlog\Resources\NewsletterResource\Pages\CreateNewsletter::route('/create'),
            'edit' => \Firefly\FilamentBlog\Resources\NewsletterResource\Pages\EditNewsletter::route('/{record}/edit'),
        ];
    }
}
