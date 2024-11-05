<?php

namespace App\Filament\Resources;

use App\Enums\V1\BookEdition;
use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\V1\Book;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("slug"),
                TextInput::make("name"),
                TextInput::make("cost"),
                Group::make()->schema([
                    Section::make("ISBNs")->schema([
                        TextInput::make("isbn_13")
                            ->numeric(),
                        TextInput::make("isbn_10")
                            ->numeric(),
                    ]),
                ]),
                Textarea::make("description")->rows(5),
                TextInput::make("jpg_image_url")
                    ->name("Image")
                    ->url(),
                Select::make("binding")
                    ->options([
                        "Paperback" => "Paperback",
                        "Hardcover" => "Hardcover",
                    ])
                    ->selectablePlaceholder(false),
                Select::make("edition")
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
                TextInput::make("author"),
                TextInput::make("publisher"),
                DateTimePicker::make("published"),
                Select::make("approved")
                    ->options([
                        0 => 0,
                        1 => 1,
                    ])
                    ->default(0)
                    ->selectablePlaceholder(false),
                Section::make("Relations")->schema([
                    Select::make("User")
                        ->relationship("user", "name")
                        ->preload()
                        ->searchable(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make("slug")->searchable(),
                TextInputColumn::make("name")->searchable(),
                TextInputColumn::make("cost")->searchable(),
                TextInputColumn::make(name: "isbn_13")->searchable(),
                TextInputColumn::make(name: "isbn_10")->searchable(),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
