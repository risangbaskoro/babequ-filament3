<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama'),
                TextInput::make('phone_number')
                    ->label('No. Handphone'),
                Radio::make('role')
                    ->options(UserRole::toArray())
                    ->inline()
                    ->label('Role')
                    ->disabled(fn(?Model $record): bool => !is_null($record) && $record === auth()->user()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('role')
                            ->sortable()
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => UserRole::from($state)->name)
                            ->color(fn(int $state): string => match ($state) {
                                1 => 'gray',
                                2 => 'primary',
                                3 => 'success',
                            }),
                    ]),
                    Stack::make([
                        TextColumn::make('email')
                            ->icon('heroicon-m-envelope'),
                        TextColumn::make('phone_number')
                            ->icon('heroicon-m-phone'),
                    ]),
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->multiple()
                    ->options(UserRole::toArray())
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
            ->checkIfRecordIsSelectableUsing(fn(Model $record): bool => $record->isNot(auth()->user()))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
