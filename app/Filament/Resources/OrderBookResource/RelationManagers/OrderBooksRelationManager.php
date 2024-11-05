<?php

namespace App\Filament\Resources\OrderBookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\V1\BookEdition;

class OrderBooksRelationManager extends RelationManager
{
    protected static string $relationship = 'orderBooks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("slug"),
                Forms\Components\TextInput::make("name"),
                Forms\Components\TextInput::make("cost"),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make("ISBNs")->schema([
                        Forms\Components\TextInput::make("isbn_13")
                            ->numeric(),
                        Forms\Components\TextInput::make("isbn_10")
                            ->numeric(),
                    ]),
                ]),
                Forms\Components\Textarea::make("description")->rows(5),
                Forms\Components\TextInput::make("jpg_image_url")
                    ->name("Image")
                    ->url(),
                Forms\Components\Select::make("binding")
                    ->options([
                        "Paperback" => "Paperback",
                        "Hardcover" => "Hardcover",
                    ])
                    ->selectablePlaceholder(false),
                Forms\Components\Select::make("edition")
                    ->options([
                        BookEdition::BIBLIOGRAPHICAL->value => BookEdition::BIBLIOGRAPHICAL->value,
                        BookEdition::COLLECTORS->value => BookEdition::COLLECTORS->value,
                        BookEdition::PUBLISHER->value => BookEdition::PUBLISHER->value,
                        BookEdition::REVISED->value => BookEdition::REVISED->value,
                        BookEdition::REVISED_UPDATED->value => BookEdition::REVISED_UPDATED->value,
                        BookEdition::CO_EDITION->value => BookEdition::CO_EDITION->value,
                        BookEdition::E_DITION->value => BookEdition::E_DITION->value,
                        BookEdition::LIBRARY->value => BookEdition::LIBRARY->value,
                        BookEdition::BOOK->value => BookEdition::BOOK->value,
                        BookEdition::CHEAP->value => BookEdition::CHEAP->value,
                        BookEdition::COLONIAL->value => BookEdition::COLONIAL->value,
                        BookEdition::CADET->value => BookEdition::CADET->value,
                        BookEdition::LARGE->value => BookEdition::LARGE->value,
                        BookEdition::CRITICAL->value => BookEdition::CRITICAL->value,
                    ])
                    ->selectablePlaceholder(false),
                Forms\Components\TextInput::make("author"),
                Forms\Components\TextInput::make("publisher"),
                Forms\Components\DateTimePicker::make("published"),
                Forms\Components\Select::make("approved")
                    ->options([
                        0 => 0,
                        1 => 1,
                    ])
                    ->default(0)
                    ->selectablePlaceholder(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make("slug")->searchable(),
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("cost")->searchable(),
                Tables\Columns\TextColumn::make(name: "isbn_13")->searchable(),
                Tables\Columns\TextColumn::make(name: "isbn_10")->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
