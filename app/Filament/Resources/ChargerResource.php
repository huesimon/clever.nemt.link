<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChargerResource\Pages;
use App\Filament\Resources\ChargerResource\RelationManagers;
use App\Models\Charger;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChargerResource extends Resource
{
    protected static ?string $model = Charger::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('evse_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location_external_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('evse_connector_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255),
                Forms\Components\TextInput::make('balance')
                    ->maxLength(255),
                Forms\Components\TextInput::make('connector_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('max_current_amp'),
                Forms\Components\TextInput::make('max_power_kw'),
                Forms\Components\TextInput::make('plug_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('power_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('speed')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evse_id'),
                Tables\Columns\TextColumn::make('location_external_id'),
                Tables\Columns\TextColumn::make('evse_connector_id'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('balance'),
                Tables\Columns\TextColumn::make('connector_id'),
                Tables\Columns\TextColumn::make('max_current_amp'),
                Tables\Columns\TextColumn::make('max_power_kw'),
                Tables\Columns\TextColumn::make('plug_type'),
                Tables\Columns\TextColumn::make('power_type'),
                Tables\Columns\TextColumn::make('speed'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChargers::route('/'),
            'create' => Pages\CreateCharger::route('/create'),
            'edit' => Pages\EditCharger::route('/{record}/edit'),
        ];
    }
}
