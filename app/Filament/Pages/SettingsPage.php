<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

/**
 * Base for every "edit one part of the site" page. All of them write to the
 * single `settings` row; each page only touches the columns it declares.
 *
 * @property-read Schema $form Provided by InteractsWithSchemas.
 */
abstract class SettingsPage extends Page
{
    protected string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    /**
     * Columns on `settings` this page owns.
     *
     * @return array<int, string>
     */
    abstract protected function settingKeys(): array;

    public function mount(): void
    {
        $this->form->fill(Setting::current()->only($this->settingKeys()));
    }

    public function save(): void
    {
        Setting::current()->update(Arr::only($this->form->getState(), $this->settingKeys()));

        Notification::make()->success()->title('Perubahan disimpan')->send();
    }

    /** @return array<int, Action> */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Simpan')->submit('save'),
        ];
    }
}
