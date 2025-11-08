<?php

namespace Molitor\User\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Molitor\User\Filament\Resources\PermissionResource\Pages;
use Molitor\User\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): string
    {
        return __('user::common.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('user::permission.title');
    }

    public static function canAccess(): bool
    {
        return Gate::allows('acl', 'permission');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('user::permission.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(__('user::permission.form.description'))
                    ->columnSpanFull(),
                Forms\Components\Select::make('userGroups')
                    ->label(__('user::permission.form.userGroups'))
                    ->multiple()
                    ->relationship('userGroups', 'name')
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user::permission.table.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('user::permission.table.description'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('userGroups')
                    ->label(__('user::permission.table.userGroups'))
                    ->getStateUsing(fn($record) => $record->userGroups->pluck('name'))
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
