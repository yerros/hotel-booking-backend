<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelImageResource\Pages;
use App\Filament\Resources\HotelImageResource\RelationManagers;
use App\Models\HotelImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class HotelImageResource extends Resource
{
    protected static ?string $model = HotelImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Hotel Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('hotel_images')
                    ->multiple()
                    ->enableReordering()
                    ->enableDownload()
                    ->enableOpen()
                    ->image()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListHotelImages::route('/'),
            'create' => Pages\CreateHotelImage::route('/create'),
            'edit' => Pages\EditHotelImage::route('/{record}/edit'),
        ];
    }
}
