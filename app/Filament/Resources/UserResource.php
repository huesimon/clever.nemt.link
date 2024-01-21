<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\LocationsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\RadiusRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\Toggle::make('notify_locations')
                    ->required(),
                Forms\Components\TextInput::make('telegram_id')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telegram_validation_code')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telegram_verified_at')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('two_factor_secret')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('two_factor_recovery_codes')
                    ->maxLength(65535),
                Forms\Components\DateTimePicker::make('two_factor_confirmed_at'),
                Forms\Components\TextInput::make('current_team_id'),
                Forms\Components\TextInput::make('profile_photo_path')
                    ->maxLength(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(isIndividual: true, isGlobal: true),
                Tables\Columns\TextColumn::make('email')->searchable(isIndividual: true, isGlobal: true),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('notify_locations')
                    ->boolean(),
                Tables\Columns\TextColumn::make('last_logged_in_at')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('last_logged_in_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getRelations(): array
    {
        return [
            LocationsRelationManager::class,
            RadiusRelationManager::class,
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
