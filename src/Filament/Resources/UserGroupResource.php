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
use Molitor\User\Filament\Resources\UserGroupResource\Pages;
use Molitor\User\Models\UserGroup;

class UserGroupResource extends Resource
{
    protected static ?string $model = UserGroup::class;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string
    {
        return __('user::common.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('user::user-group.title');
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
                    ->label(__('user::user-group.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(__('user::user-group.form.description'))
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_default')
                    ->label(__('user::user-group.form.is_default')),
                Forms\Components\Select::make('permissions')
                    ->label(__('user::permission.title'))
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label(__('user::user-group.table.is_default')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user::user-group.table.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('user::user-group.table.description'))
                    ->searchable(),
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
            'index' => Pages\ListUserGroups::route('/'),
            'create' => Pages\CreateUserGroup::route('/create'),
            'edit' => Pages\EditUserGroup::route('/{record}/edit'),
        ];
    }
}
