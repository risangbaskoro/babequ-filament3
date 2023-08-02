<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                    ->native(false)
                    ->selectablePlaceholder(false)
                    ->visible(auth()->user()->isAdmin())
                    ->options(ProductStatus::toArray())
                    ->disabled(function (Page $livewire) {
                        return $livewire instanceof Pages\CreateProduct;
                    })
                    ->disabled(!auth()->user()->isAdmin())
                    ->default(1)
                    ->label('Status')
                    ->columnSpan('full'),

                TextInput::make('name')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        $slug = Str::slug($state);
                        if (static::getModel()::where('slug', $slug)->exists()) {
                            $slug = Str::slug($slug . '-' . Str::lower(Str::random(4)));
                            $set('slug', $slug);
                        } else {
                            $set('slug', $slug);
                        }
                    })
                    ->label('Nama')
                    ->required()
                    ->placeholder('Nama')
                    ->disabled(function (Page $livewire) {
                        return $livewire instanceof Pages\EditProduct;
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->placeholder('Slug')
                    ->disabled()
                    ->unique('products', ignoreRecord: true)
                    ->dehydrated(),
                TextInput::make('price')
                    ->prefix('Rp')
                    ->label('Harga')
                    ->required()
                    ->mask(RawJs::make(
                        <<<'JS'
                            $money($input, ',', '.', 0)
                        JS
                    ))
                    ->lazy()
                    ->afterStateUpdated(function (callable $set): void {
                        $set('status', 1);
                    }),
                Select::make('category_id')
                    ->options(Category::query()->pluck('name', 'id'))
                    ->searchable()
                    ->label('Kategori')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function (callable $set): void {
                        $set('status', 1);
                    }),
                RichEditor::make('description')
                    ->disableAllToolbarButtons()
                    ->enableToolbarButtons([
                        'bold',
                        'italic',
                        'h2',
                        'h3',
                    ])
                    ->label('Deskripsi')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function (callable $set): void {
                        $set('status', 1);
                    })
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
