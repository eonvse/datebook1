<?php

namespace App\Filament\Resources\MaterialResource\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';
    protected static ?string $modelLabel = 'Файл';
    protected static ?string $pluralModelLabel = 'Файлы';

    public function form(Form $form): Form
    {

        $storage_dir = 'materials/'.$this->getOwnerRecord()->slug;

        return $form
        ->schema([
            Forms\Components\FileUpload::make('url')
                ->translateLabel()
                ->required()
                ->disk('public')
                ->directory($storage_dir)
                ->storeFileNamesIn('name'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Files'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('File name')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add file'))
                    ->successNotificationTitle(__('File added')),
            ])
            ->actions([
                Tables\Actions\Action::make('View') //просмотр картинок и pdf в новом окне
                    ->label(__('View url'))
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->color('gray')
                    ->url(fn($record) => Storage::url($record->url))
                    ->openUrlInNewTab()
                    ->visible(function($record) {
                        $mime =  Storage::mimeType($record->url);
                        $allowType = ['image/jpeg','image/png','image/gif','application/pdf','audio/mpeg'];
                        if (in_array($mime,$allowType)) return true;
                        return false;
                    }),

                //скачать файл
                Tables\Actions\Action::make('Download')
                    ->label(__('Download'))
                    ->icon('heroicon-m-arrow-down-tray')
                    ->iconButton()
                    ->color('info')
                    ->action(fn($record) => Storage::download($record->url, $record->name)),
                Tables\Actions\DeleteAction::make() //удалить файл
                    ->iconButton()
                    ->before(function ($record) {
                        Storage::disk('public')->delete($record->url);
                    })
                    ->successNotificationTitle(__('File deleted')),
            ])
            ->bulkActions([
                /*Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),*/
            ]);
    }

    protected function configureCreateAction(Tables\Actions\CreateAction $action): void
    {
        $action
            ->authorize(static fn (RelationManager $livewire): bool => ((! $livewire->isReadOnly()) && $livewire->canCreate()) || (! $livewire->isReadOnly() && $livewire->getOwnerRecord()->user_id === Filament::auth()->id()))
            ->form(fn (Form $form): Form => $this->form($form->columns(1)))
            ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = Filament::auth()->id();

                return $data;
            });
    }

    public function isReadOnly(): bool
    {
        return false;
    }

}
