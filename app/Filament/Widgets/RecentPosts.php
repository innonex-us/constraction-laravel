<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPosts extends BaseWidget
{
    protected static ?int $sort = 11;

    protected static ?string $heading = 'Recent Posts';

    protected int|string|array $columnSpan = [
        'md' => 12,
        'xl' => 4,
    ];

    protected ?string $placeholderHeight = '360px';

    public function table(Table $table): Table
    {
        return $table
            ->query(Post::query()->latest())
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('title')->searchable()->limit(28),
                IconColumn::make('is_published')->boolean()->label('Published'),
                TextColumn::make('published_at')->dateTime()->since()->label('When'),
            ]);
    }
}
