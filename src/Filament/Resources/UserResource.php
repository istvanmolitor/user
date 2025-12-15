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
use Molitor\User\Filament\Resources\UserResource\Pages;
use Molitor\User\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string
    {
        return __('user::common.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('user::user.title');
    }

    public static function canAccess(): bool
    {
        return Gate::allows('acl', 'permission');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Toggle::make('email_verified_at')
                    ->label(__('Ellenőrizve'))
                    ->inline(false)
                    ->afterStateHydrated(function (Forms\Components\Toggle $component, $state): void {
                        $component->state(! empty($state));
                    })
                    ->dehydrateStateUsing(function ($state) {
                        return $state ? now() : null;
                    }),
                Forms\Components\Select::make('userGroups')
                    ->label(__('user::user.title'))
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
                Tables\Columns\TextColumn::make('email')
                    ->label(__('user::user.table.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user::user.table.username'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('userGroups.name')
                    ->label(__('user::user.table.user_groups'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
