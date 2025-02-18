<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
// use Filament\Actions\Action as ActionsAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static ?string $navigationGroup = 'Products';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // ActionsAction::make('New Order')
            //     ->url(fn(): string => OrderResource::getUrl('create')),
        ];
    }
}
