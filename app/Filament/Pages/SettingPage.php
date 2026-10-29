<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class SettingPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $slug = 'pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.setting-page';

    public ?array $data = [];

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::SevenExtraLarge;
    }

    public function mount(): void
    {
        $setting = Setting::current();
        $this->form->fill($setting->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('PengaturanInstansi')
                    ->tabs([
                        Tabs\Tab::make('Kepala Sekolah')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Grid::make(['default' => 1, 'sm' => 2])->schema([
                                    TextInput::make('nama_kepala_sekolah')
                                        ->label('Nama Lengkap & Gelar')
                                        ->placeholder('Contoh: Drs. H. Ahmad Sudrajat, M.Pd.')
                                        ->required(),

                                    TextInput::make('nip_kepala_sekolah')
                                        ->label('NIP Kepala Sekolah')
                                        ->placeholder('Contoh: 196805121994031005')
                                        ->required(),
                                ]),
                            ]),

                        Tabs\Tab::make('Spesimen')
                            ->icon('heroicon-o-finger-print')
                            ->schema([
                                Toggle::make('gunakan_spesimen')
                                    ->label('Gunakan Spesimen')
                                    ->default(false),

                                Grid::make(['default' => 1, 'md' => 2])->schema([
                                    FileUpload::make('spesimen_ttd')
                                        ->label('Spesimen Tanda Tangan')
                                        ->disk('public_uploads')
                                        ->directory('spesimen')
                                        ->visibility('public')
                                        ->image()
                                        ->acceptedFileTypes(['image/png'])
                                        ->imagePreviewHeight('130')
                                        ->helperText('Wajib format PNG transparan.'),

                                    FileUpload::make('spesimen_stempel')
                                        ->label('Spesimen Stempel Dinas')
                                        ->disk('public_uploads')
                                        ->directory('spesimen')
                                        ->visibility('public')
                                        ->image()
                                        ->acceptedFileTypes(['image/png'])
                                        ->imagePreviewHeight('130')
                                        ->helperText('Wajib format PNG transparan.'),
                                ]),
                            ]),

                        Tabs\Tab::make('TTE')
                            ->icon('heroicon-o-qr-code')
                            ->schema([
                                Toggle::make('gunakan_tte')
                                    ->label('Gunakan Tanda Tangan Elektronik (TTE)')
                                    ->default(false),

                                TextInput::make('teks_tte')
                                    ->label('Kode / Tag Penanda TTE Bebas')
                                    ->placeholder('# $(pengirim)')
                                    ->helperText('Penanda atau tag bebas yang dikenali oleh layanan TTE.')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $setting = Setting::current();
        $setting->update($data);

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Data kepala sekolah dan spesimen tanda tangan/stempel telah diperbarui.')
            ->send();
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->icon('heroicon-o-check')
                ->action('save')
                ->color('primary'),
        ];
    }
}
