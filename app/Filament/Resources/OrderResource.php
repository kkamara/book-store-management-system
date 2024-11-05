<?php

namespace App\Filament\Resources;

use App\Enums\V1\OrderStatus;
use App\Filament\Resources\OrderBookResource\RelationManagers\OrderBooksRelationManager;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\V1\Order;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("user")
                    ->relationship("user", "name")
                    ->preload()
                    ->searchable(),
                TextInput::make("cost"),
                TextInput::make("delivery_cost"),
                Select::make("status")
                    ->options(OrderStatus::class)
                    ->selectablePlaceholder(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make("user.name"),
                TextInputColumn::make("cost"),
                TextInputColumn::make("delivery_cost"),
                SelectColumn::make("status")
                    ->options(OrderStatus::class)
                    ->selectablePlaceholder(false),
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
            OrderBooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
