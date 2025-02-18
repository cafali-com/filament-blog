<?php

namespace Firefly\FilamentBlog\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Firefly\FilamentBlog\Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Descriptor\TextDescriptor;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo',
        'favicon',
        'organization_name',
        'google_console_code',
        'google_analytic_code',
        'google_adsense_code',
        'quick_links',
    ];

    protected $casts = [
        'quick_links' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function getLogoImageAttribute()
    {
        return asset('storage/' . $this->logo);
    }

    protected function getFavIconImageAttribute()
    {
        return asset('storage/' . $this->favicon);
    }

    protected static function newFactory()
    {
        return new SettingFactory();
    }

    public static function getForm(): array
    {
        return [
            Section::make(trans('filament-blog::cafali-blog.settings.general_information'))
                ->schema([
                    TextInput::make('title')
                        ->label(trans('filament-blog::cafali-blog.settings.title'))
                        ->maxLength(155)
                        ->required(),
                    TextInput::make('organization_name')
                        ->label(trans('filament-blog::cafali-blog.settings.organization'))
                        ->required()
                        ->maxLength(155)
                        ->minLength(3),
                    Textarea::make('description')
                        ->label(trans('filament-blog::cafali-blog.settings.description'))
                        ->required()
                        ->minLength(10)
                        ->maxLength(1000)
                        ->columnSpanFull(),
                    FileUpload::make('logo')
                        ->label(trans('filament-blog::cafali-blog.settings.logo'))
                        ->hint('Max height 400')
                        ->directory('setting/logo')
                        ->maxSize(1024 * 1024 * 2)
                        ->rules('dimensions:max_height=400')
                        ->nullable()->columnSpanFull(),
                    FileUpload::make('favicon')
                        ->label(trans('filament-blog::cafali-blog.settings.favicon'))
                        ->directory('setting/favicon')
                        ->maxSize(50 )
                        ->nullable()->columnSpanFull()
                ])->columns(2),

            Section::make('SEO')
                ->description(trans('filament-blog::cafali-blog.settings.seo_description'))
                ->schema([
                    Textarea::make('google_console_code')
                        ->label(trans('filament-blog::cafali-blog.settings.google_console_code'))
                        ->startsWith('<meta')
                        ->nullable()
                        ->columnSpanFull(),
                    Textarea::make('google_analytic_code')
                        ->label(trans('filament-blog::cafali-blog.settings.google_analytics_code'))
                        ->startsWith('<script')
                        ->endsWith('</script>')
                        ->nullable()
                        ->columnSpanFull(),
                    Textarea::make('google_adsense_code')
                        ->label(trans('filament-blog::cafali-blog.settings.google_adsense_code'))
                        ->startsWith('<script')
                        ->endsWith('</script>')
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),
            Section::make(trans('filament-blog::cafali-blog.settings.quick_links'))
                ->description(trans('filament-blog::cafali-blog.settings.quick_links_description'))
                ->schema([
                    Repeater::make('quick_links')
                        ->label(trans('filament-blog::cafali-blog.settings.links'))
                        ->schema([
                            TextInput::make('label')
                                ->label(trans('filament-blog::cafali-blog.settings.label'))
                                ->required()
                                ->maxLength(155),
                            TextInput::make('url')
                                ->label('URL')
                                ->helperText('URL should start with http:// or https://')
                                ->required()
                                ->url()
                                ->maxLength(255),
                        ])->columns(2),
                ])->columnSpanFull(),
        ];
    }

    public function getTable()
    {
        return config('filamentblog.tables.prefix') . 'settings';
    }
}
