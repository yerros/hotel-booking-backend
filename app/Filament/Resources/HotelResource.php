<?php

namespace App\Filament\Resources;

use App\Models\Hotel;
use App\Models\Amenity;
use App\Filament\Resources\HotelResource\Pages;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Components\{TextInput, Textarea, Select, Grid, Toggle, SpatieMediaLibraryFileUpload};
use Filament\Tables;
use Filament\Tables\Columns\{TextColumn, IconColumn};

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Hotel Management';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')->required(),
                        Select::make('category')
                            ->options([
                                'luxury' => 'Luxury',
                                'business' => 'Business',
                                'budget' => 'Budget',
                                'resort' => 'Resort',
                            ])->required(),
                        TextInput::make('city')->required(),
                        TextInput::make('country')->required(),
                        TextInput::make('address')->columnSpan(2),
                        Textarea::make('description')->columnSpan(2),
                        TextInput::make('latitude')->numeric(),
                        TextInput::make('longitude')->numeric(),
                        TextInput::make('rating')->numeric()->default(0.0),
                        TextInput::make('distance_from_center')->numeric()->suffix('km'),
                        Select::make('amenities')
                            ->multiple()
                            ->relationship('amenities', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Hotel Amenities')
                            ->columnSpan(2),
                    ]),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('hotel_images')
                    ->multiple()
                    ->image()
                    ->enableReordering()
                    ->enableDownload()
                    ->enableOpen()
                    ->columnSpanFull()
                    ->label('Upload Hotel Images'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('category'),
                TextColumn::make('city'),
                TextColumn::make('rating')->sortable(),
                TextColumn::make('country'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
