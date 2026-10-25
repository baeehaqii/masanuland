<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Models\Lead;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Proyek';

    protected static ?string $modelLabel = 'Lead';

    protected static ?string $pluralModelLabel = 'Leads';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('project.name')->label('Perumahan')->placeholder('Perumahan sudah dihapus'),
            TextEntry::make('created_at')->label('Masuk')->dateTime('d M Y, H:i'),
            TextEntry::make('name')->label('Nama'),
            TextEntry::make('phone')->label('No. WhatsApp')->placeholder('-')->copyable(),
            TextEntry::make('message')->label('Pesan')->placeholder('-')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Masuk')->since()
                    ->dateTimeTooltip('d M Y, H:i')->sortable(),
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('phone')->label('No. WhatsApp')->searchable()->copyable()->placeholder('-'),
                TextColumn::make('project.name')->label('Perumahan')->badge()->sortable()
                    ->placeholder('Perumahan sudah dihapus'),
                TextColumn::make('message')->label('Pesan')->limit(60)->wrap()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('project')->label('Perumahan')
                    ->relationship('project', 'name')->preload(),
            ])
            ->recordActions([
                Action::make('whatsapp')->label('Chat')->icon(Heroicon::OutlinedChatBubbleOvalLeft)
                    ->color('success')
                    // "0812-…" → "62812…"; WhatsApp menolak awalan 0.
                    ->url(fn (Lead $record) => 'https://wa.me/'.preg_replace('/^0/', '62', preg_replace('/\D/', '', (string) $record->phone)), shouldOpenInNewTab: true)
                    ->visible(fn (Lead $record) => filled($record->phone)),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
        ];
    }
}
