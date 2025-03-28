<?php

namespace Firefly\FilamentBlog\Models;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use FilamentTiptapEditor\TiptapEditor;
use Firefly\FilamentBlog\Database\Factories\PostFactory;
use Firefly\FilamentBlog\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'sub_title',
        'body',
        'status',
        'published_at',
        'scheduled_for',
        'cover_photo_path',
        'photo_alt_text',
        'user_id',
    ];

    protected $dates = [
        'scheduled_for',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'published_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'status' => PostStatus::class,
        'user_id' => 'integer',
    ];

    protected static function newFactory()
    {
        return new PostFactory();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, config('filamentblog.tables.prefix').'category_'.config('filamentblog.tables.prefix').'post');
    }

    public function comments(): hasmany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class,config('filamentblog.tables.prefix').'post_'.config('filamentblog.tables.prefix').'tag');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filamentblog.user.model'), config('filamentblog.user.foreign_key'));
    }

    public function seoDetail()
    {
        return $this->hasOne(SeoDetail::class);
    }

    public function isNotPublished()
    {
        return ! $this->isStatusPublished();
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', PostStatus::PUBLISHED)->latest('published_at');
    }

    public function scopeScheduled(Builder $query)
    {
        return $query->where('status', PostStatus::SCHEDULED)->latest('scheduled_for');
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', PostStatus::PENDING)->latest('created_at');
    }

    public function formattedPublishedDate()
    {
        return $this->published_at?->format('d M Y');
    }

    public function isScheduled()
    {
        return $this->status === PostStatus::SCHEDULED;
    }

    public function isStatusPublished()
    {
        return $this->status === PostStatus::PUBLISHED;
    }

    public function relatedPosts($take = 3)
    {
        return $this->whereHas('categories', function ($query) {
            $query->whereIn(config('filamentblog.tables.prefix').'categories.id', $this->categories->pluck('id'))
                ->whereNotIn(config('filamentblog.tables.prefix').'posts.id', [$this->id]);
        })->published()->with('user')->take($take)->get();
    }

    protected function getFeaturePhotoAttribute()
    {
        return asset('storage/'.$this->cover_photo_path);
    }

    public static function getForm()
    {
        return [
            Section::make(trans('filament-blog::filament-blog.posts.blog_details'))
                ->schema([
                    Fieldset::make('Titles')
                        ->label(trans('filament-blog::filament-blog.posts.titles'))
                        ->schema([
                            Select::make('category_id')
                                ->multiple()
                                ->preload()
                                ->createOptionForm(Category::getForm())
                                ->searchable()
                                ->relationship('categories', 'name')
                                ->label(trans('filament-blog::filament-blog.posts.categories'))
                                ->columnSpanFull(),

                            TextInput::make('title')
                                ->label(trans('filament-blog::filament-blog.posts.title'))
                                ->live(true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                    'slug',
                                    Str::slug($state)
                                ))
                                ->required()
                                ->unique(config('filamentblog.tables.prefix').'posts', 'title', null, 'id')
                                ->maxLength(255),

                            TextInput::make('slug')
                                ->maxLength(255),

                            Textarea::make('sub_title')
                                ->label(trans('filament-blog::filament-blog.posts.sub_title'))
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Select::make('tag_id')
                                ->multiple()
                                ->preload()
                                ->createOptionForm(Tag::getForm())
                                ->searchable()
                                ->relationship('tags', 'name')
                                ->label(trans('filament-blog::filament-blog.posts.tags'))
                                ->columnSpanFull(),
                        ]),
                    TiptapEditor::make('body')
                        ->label(trans('filament-blog::filament-blog.posts.body'))
                        ->profile('default')
                        ->disableFloatingMenus()
                        ->extraInputAttributes(['style' => 'max-height: 30rem; min-height: 24rem'])
                        ->required()
                        ->disk('s3')
                        ->directory('blog')
                        ->visibility('public')
                        ->columnSpanFull(),
                    //TODO: We need to add a separate migration fot the cover_photo_path
                    Fieldset::make('Feature Image')
                        ->label(trans('filament-blog::filament-blog.posts.feature_image'))
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('cover_photo_path')
//                            FileUpload::make('cover_photo_path')
                                ->label(trans('filament-blog::filament-blog.posts.cover_photo'))
////                                ->directory('/blog-feature-images')
                                ->disk('s3')
                                ->collection('blog')
//                                ->directory('blog')
                                ->visibility('public')
                                ->hint(trans('filament-blog::filament-blog.posts.feature_image_hint'))
                                ->image()
                                ->preserveFilenames()
                                ->imageEditor()
                                ->maxSize(1024 * 5)
                                ->rules('dimensions:max_width=1920,max_height=1004')
                                ->required(),
                            TextInput::make('photo_alt_text')
                                ->label(trans('filament-blog::filament-blog.posts.photo_alt_text'))
                                ->required(),
                        ])->columns(1),

                    Fieldset::make('Status')
                        ->label(trans('filament-blog::filament-blog.posts.status'))
                        ->schema([

                            ToggleButtons::make('status')
                                ->label(trans('filament-blog::filament-blog.posts.status'))
                                ->live()
                                ->inline()
                                ->options(PostStatus::class)
                                ->required(),

                            DateTimePicker::make('scheduled_for')
                                ->label(trans('filament-blog::filament-blog.posts.scheduled_for'))
                                ->visible(function ($get) {
                                    return $get('status') === PostStatus::SCHEDULED->value;
                                })
                                ->required(function ($get) {
                                    return $get('status') === PostStatus::SCHEDULED->value;
                                })
                                ->minDate(now()->addMinutes(5))
                                ->native(false),
                        ]),
                    Select::make(config('filamentblog.user.foreign_key'))
//                        ->relationship('user', config('filamentblog.user.columns.name'))
                        ->options(config('filamentblog.user.model')::where('is_admin', true)->pluck('name', 'id'))
                        ->label(trans('filament-blog::filament-blog.posts.author'))
                        ->nullable(false)
                        ->default(auth()->id()),

                ]),
        ];
    }

    public function getTable()
    {
        return config('filamentblog.tables.prefix') . 'posts';
    }
}
