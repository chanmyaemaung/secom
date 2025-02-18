<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\TagsRelationManager;
use App\Filament\Resources\CategoryResource;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\search;

class ProductResource extends Resource
{
    private static array $statuses = [
        'in stock' => 'In Stock',
        'sold out' => 'Sold Out',
        'coming soon' => 'Coming Soon',
    ];


    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            // Tab
            ->schema([
                // Tabs
                /* Tabs::make()->tabs([
                    Tab::make('Main Data')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->placeholder('Product name')
                                ->unique(ignoreRecord: true),
                            TextInput::make('price')
                                ->required()
                                ->placeholder('Product price')
                                ->rules(['numeric']),
                            RichEditor::make('description')
                                ->columnSpanFull()
                                ->required(),
                        ]),

                    Tab::make('Additional Data')
                        ->schema([
                            Radio::make('status')
                                ->options(self::$statuses),
                            Select::make('category_id')
                                ->relationship('category', 'name'),
                            Select::make('tags')
                                ->relationship('tags', 'name')
                                ->preload()
                                ->multiple(),
                        ])
                ]), */

                Wizard::make([
                    Step::make('Main Data')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->placeholder('Product name')
                                ->unique(ignoreRecord: true),
                            TextInput::make('price')
                                ->required()
                                ->placeholder('Product price')
                                ->rules(['numeric']),
                            RichEditor::make('description')
                                ->columnSpanFull()
                                ->required(),
                        ]),
                    Step::make('Additional Data')
                        ->schema([
                            Radio::make('status')
                                ->options(self::$statuses),
                            Select::make('category_id')
                                ->relationship('category', 'name'),
                            Select::make('tags')
                                ->relationship('tags', 'name')
                                ->preload()
                                ->multiple(),
                        ]),
                ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /* TextInputColumn::make('name')
                    ->rules(['required', 'min:3'])
                    ->sortable()
                    ->searchable(), */
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                // ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('price')
                    ->sortable()
                    ->searchable()
                    ->money('USD')
                    ->getStateUsing(function (Product $record): float {
                        return $record->price / 100;
                    })
                    ->alignEnd(),
                // ToggleColumn::make('is_active'),
                CheckboxColumn::make('is_active'),
                /* TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in stock' => 'primary',
                        'sold out' => 'danger',
                        'coming soon' => 'info',
                        default => 'primary',
                    }), */
                SelectColumn::make('status')
                    ->options(self::$statuses),
                TextColumn::make('category.name')
                    ->label('Category Name'),
                // ->url(fn(Product $product): string => CategoryResource::getUrl('edit', ['record' => $product->category_id])),
                TextColumn::make('tags.name')->badge(),
                TextColumn::make('created_at')
                    // ->dateTime('d/m/Y H:i')
                    // ->date()
                    ->since(),

            ])
            ->defaultSort('price', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(self::$statuses),
                SelectFilter::make('catetgory')
                    ->relationship('category', 'name'),
                Filter::make('created_from')
                    ->form([
                        DatePicker::make('created_from'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    }),
                Filter::make('created_until')
                    ->form([
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
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
            TagsRelationManager::class,
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
