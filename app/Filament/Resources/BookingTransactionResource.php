<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use PhpParser\Node\Stmt\Label;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('phone_number')
                    ->required()
                    ->numeric(),

                TextInput::make('booking_trx_id')
                    ->required()
                    ->maxLength(255),

                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),

                TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->prefix('Days'),

                DatePicker::make('started_at')
                    ->required(),

                DatePicker::make('ended_at')
                    ->required(),

                Select::make('is_paid')
                    ->options([
                        true => 'Paid',
                        false => 'Not Paid'
                    ])
                    ->required(),

                Select::make('office_space_id')
                    ->relationship('OfficeSpace', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('booking_trx_id')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->prefix('IDR ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->suffix(' Days')
                    ->sortable(),

                Tables\Columns\TextColumn::make('started_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ended_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->label('Paid'),

                Tables\Columns\TextColumn::make('officeSpace.name')
                    ->label('Office Space')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action((function (BookingTransaction $record) {
                        $record->is_paid = true;
                        $record->save();
                    }))

                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn(BookingTransaction $record) => !$record->is_paid)
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
