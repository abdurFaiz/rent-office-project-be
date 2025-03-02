<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeSpaceResource\Pages;
use App\Filament\Resources\OfficeSpaceResource\RelationManagers;
use App\Models\OfficeSpace;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;


class OfficeSpaceResource extends Resource
{
    protected static ?string $model = OfficeSpace::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->helperText('Gunakan nama data yang tepat')
                ->maxLength(255),

                TextInput::make('address')
                ->required()
                ->helperText('Gunakan nama data yang tepat')
                ->maxLength(255),

                FileUpload::make('thumbnail')
                ->required()
                ->image(),
           
                Textarea::make('about')
                ->required()
                ->rows(10)
                ->cols(20),
// repeter ini memunginkan user untuk dapat mengupload photo atau file lebih dari satu, dan disimpan ke table officephotos(ditandai dengan keyword schema)
                Repeater::make('photos')
                ->relationship('photos')
                ->schema([FileUpload::make('photo')
                ->required(),
            ]),

                Repeater::make('benefits')
                ->relationship('benefits')
                ->schema([TextInput::make('name')
                ->required(),
            ]),
// kemudian relationship disini berfungsi untuk hanya mengambil data dari table yg sudah ada
            Select::make('city_id')
            ->relationship('city', 'name')
            ->searchable()
            ->preload()
            ->required(),

            TextInput::make('price')
            ->required()
            ->numeric()
            ->prefix('IDR'),

            TextInput::make('duration')
            ->required()
            ->prefix('Days')
            ->numeric(),

            Select::make('is_open')
            ->options([
                true => 'Open',
                false => 'Not Open'
            ])
            ->required(),

            Select::make('is_full_booked')
            ->options([
                true => 'Not Available',
                false => 'Available'
            ])
            ->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),

                TextColumn::make('about'),

                TextColumn::make('city.name')
                ->searchable(),

                TextColumn::make('address')
                ->searchable(),

                ImageColumn::make('thumbnail'),

                ImageColumn::make('photos'),

                ImageColumn::make('benefits'),

                IconColumn::make('is_full_booked')
                ->boolean()
                ->trueColor('danger')
                ->falseColor('success')
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->label('Available'),

                IconColumn::make('is_open')
                ->boolean()
                ->trueColor('danger')
                ->falseColor('success')
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->label('Available'),

                TextColumn::make('price')
                ->searchable(),

                TextColumn::make('duration')
                ->searchable(),
            ])
            ->filters([
                SelectFilter::make('city_id')
                ->label('City')
                ->relationship('city', 'name')
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
            'index' => Pages\ListOfficeSpaces::route('/'),
            'create' => Pages\CreateOfficeSpace::route('/create'),
            'edit' => Pages\EditOfficeSpace::route('/{record}/edit'),
        ];
    }
}
