<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Filament\Resources\LocationResource\RelationManagers\AddressRelationManager;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('external_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_id')
                    ->required(),
                Forms\Components\TextInput::make('views')
                    ->required()
                    ->default(0),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('origin')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_roaming_allowed')
                    ->required(),
                Forms\Components\TextInput::make('is_public_visible')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('coordinates')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id'),
                Tables\Columns\TextColumn::make('company_id'),
                Tables\Columns\TextColumn::make('views')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(isGlobal: true),
                Tables\Columns\TextColumn::make('origin'),
                Tables\Columns\IconColumn::make('is_roaming_allowed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('is_public_visible'),
                Tables\Columns\TextColumn::make('coordinates'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChargersRelationManager::class,
            AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
