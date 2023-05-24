<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use App\Filament\Resources\ChargerResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChargersRelationManager extends RelationManager
{
    protected static string $relationship = 'chargers';

    protected static ?string $recordTitleAttribute = 'evse_id';

    public static function form(Form $form): Form
    {
        return ChargerResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evse_id'),
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
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
