<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmenitiesResource\Pages;
use Guava\FilamentIconPicker\Forms\IconPicker;
use App\Models\Amenities;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class AmenitiesResource extends Resource
{
    protected static ?string $model = Amenities::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Hotel Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),
                IconPicker::make('icon')
                    ->label('Icon')// kamu bisa pilih set icon lain
                    ->sets(['fontawesome-solid', 'feather'])
                    ->columns(6)
                    ->required(),
                Select::make('category')
                    ->options([
                        'facility' => 'Facility',
                        'room'     => 'Room',
                        'connectivity' => 'Connectivity',
                        'entertainment' => 'Entertainment',
                        'other'    => 'Other',
                    ])
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                IconColumn::make('icon')
                    ->label('Icon')
                    ->icon(fn($record) => $record->icon)
                    ->extraAttributes(['class' => 'text-xl']) // lebih besar tampilannya
                    ->color('primary'),
                TextColumn::make('category')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenities::route('/create'),
            'edit' => Pages\EditAmenities::route('/{record}/edit'),
        ];
    }
}
