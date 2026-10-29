<?php

namespace App\Filament\Pages;

use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class MediaPage extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-photo';

    protected static ?string $slug = 'media';

    protected static ?string $title = 'Kelola Media';

    protected static ?string $navigationLabel = 'Media';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.media-page';

    public string $category = 'all';

    public string $search = '';

    public array $selectedFiles = [];

    public bool $selectAll = false;

    public ?string $previewUrl = null;

    public ?string $previewType = null;

    public ?string $previewTitle = null;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->selectedFiles = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedFiles = collect($this->mediaItems)->pluck('relative_path')->toArray();
        } else {
            $this->selectedFiles = [];
        }
    }

    public function updatedSearch(): void
    {
        $this->selectedFiles = [];
        $this->selectAll = false;
    }

    /**
     * Get statistics of stored media files
     */
    public function getStatsProperty(): array
    {
        $uploadPath = public_path('uploads');

        if (! File::exists($uploadPath)) {
            return [
                'total_count' => 0,
                'total_size' => '0 B',
                'presensi_count' => 0,
                'presensi_size' => '0 B',
                'spesimen_count' => 0,
                'spesimen_size' => '0 B',
                'pdf_count' => 0,
                'pdf_size' => '0 B',
            ];
        }

        $allFiles = File::allFiles($uploadPath);

        $totalSize = 0;
        $presensiCount = 0;
        $presensiSize = 0;
        $spesimenCount = 0;
        $spesimenSize = 0;
        $otherCount = 0;
        $otherSize = 0;
        $totalCount = 0;
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];

        foreach ($allFiles as $file) {
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, $imageExtensions)) {
                continue;
            }

            $size = $file->getSize();
            $totalSize += $size;
            $totalCount++;

            $relPath = str_replace('\\', '/', $file->getRelativePathname());

            if (str_starts_with($relPath, 'presensi/')) {
                $presensiCount++;
                $presensiSize += $size;
            } elseif (str_starts_with($relPath, 'spesimen/')) {
                $spesimenCount++;
                $spesimenSize += $size;
            } else {
                $otherCount++;
                $otherSize += $size;
            }
        }

        return [
            'total_count' => $totalCount,
            'total_size' => $this->formatBytes($totalSize),
            'presensi_count' => $presensiCount,
            'presensi_size' => $this->formatBytes($presensiSize),
            'spesimen_count' => $spesimenCount,
            'spesimen_size' => $this->formatBytes($spesimenSize),
            'other_count' => $otherCount,
            'other_size' => $this->formatBytes($otherSize),
        ];
    }

    /**
     * Get scanned media items list
     */
    public function getMediaItemsProperty(): array
    {
        $uploadPath = public_path('uploads');

        if (! File::exists($uploadPath)) {
            return [];
        }

        $allFiles = File::allFiles($uploadPath);

        $corrections = AttendanceCorrection::with('employee')
            ->whereNotNull('foto_datang')
            ->orWhereNotNull('foto_pulang')
            ->get();

        $setting = Setting::first();
        $reports = AttendanceReport::whereNotNull('pdf_file_path')->get();

        $fotoDatangMap = [];
        $fotoPulangMap = [];

        foreach ($corrections as $c) {
            if ($c->foto_datang) {
                $clean = ltrim(str_replace('uploads/', '', $c->foto_datang), '/');
                $fotoDatangMap[$clean] = [
                    'employee_nama' => $c->employee?->nama ?? 'Pegawai',
                    'tanggal' => $c->tanggal ? $c->tanggal->format('d/m/Y') : '-',
                    'status' => $c->status_presensi,
                ];
            }
            if ($c->foto_pulang) {
                $clean = ltrim(str_replace('uploads/', '', $c->foto_pulang), '/');
                $fotoPulangMap[$clean] = [
                    'employee_nama' => $c->employee?->nama ?? 'Pegawai',
                    'tanggal' => $c->tanggal ? $c->tanggal->format('d/m/Y') : '-',
                    'status' => $c->status_presensi,
                ];
            }
        }

        $items = [];
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];

        /** @var SplFileInfo $file */
        foreach ($allFiles as $file) {
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, $imageExtensions)) {
                continue;
            }

            $relPath = str_replace('\\', '/', $file->getRelativePathname());

            if (str_starts_with($relPath, 'presensi/')) {
                $fileCat = 'presensi';
                $categoryLabel = 'Foto Presensi Geotag';
            } elseif (str_starts_with($relPath, 'spesimen/')) {
                $fileCat = 'spesimen';
                $categoryLabel = 'Spesimen & Stempel';
            } elseif (str_starts_with($relPath, 'logo/')) {
                $fileCat = 'logo';
                $categoryLabel = 'Logo Instansi';
            } else {
                $fileCat = 'other';
                $categoryLabel = 'Media Umum';
            }

            if ($this->category !== 'all' && $this->category !== $fileCat) {
                continue;
            }

            $refType = 'Arsip Bebas';
            $refText = 'Berkas Mandiri (Tidak Terikat Data)';
            $badgeColor = 'gray';

            if (isset($fotoDatangMap[$relPath])) {
                $refType = 'Foto Datang';
                $refText = $fotoDatangMap[$relPath]['employee_nama'].' • '.$fotoDatangMap[$relPath]['tanggal'];
                $badgeColor = 'info';
            } elseif (isset($fotoPulangMap[$relPath])) {
                $refType = 'Foto Pulang';
                $refText = $fotoPulangMap[$relPath]['employee_nama'].' • '.$fotoPulangMap[$relPath]['tanggal'];
                $badgeColor = 'warning';
            } elseif ($setting && ($setting->spesimen_ttd === $relPath || $setting->spesimen_ttd === 'uploads/'.$relPath)) {
                $refType = 'Spesimen TTD';
                $refText = 'Tanda Tangan Kepala Sekolah ('.$setting->nama_kepala_sekolah.')';
                $badgeColor = 'success';
            } elseif ($setting && ($setting->spesimen_stempel === $relPath || $setting->spesimen_stempel === 'uploads/'.$relPath)) {
                $refType = 'Spesimen Stempel';
                $refText = 'Stempel Dinas Resmi SMAN 1 Malingping';
                $badgeColor = 'primary';
            }

            if (! empty($this->search)) {
                $term = strtolower(trim($this->search));
                $searchPool = strtolower($file->getFilename().' '.$relPath.' '.$refType.' '.$refText);
                if (! str_contains($searchPool, $term)) {
                    continue;
                }
            }

            $items[] = [
                'filename' => $file->getFilename(),
                'relative_path' => $relPath,
                'url' => asset('uploads/'.$relPath),
                'size_bytes' => $file->getSize(),
                'size_human' => $this->formatBytes($file->getSize()),
                'extension' => strtoupper($ext),
                'is_image' => true,
                'is_pdf' => false,
                'category' => $fileCat,
                'category_label' => $categoryLabel,
                'ref_type' => $refType,
                'ref_text' => $refText,
                'badge_color' => $badgeColor,
                'modified_timestamp' => $file->getMTime(),
                'modified_at' => date('d/m/Y H:i', $file->getMTime()),
            ];
        }

        usort($items, fn ($a, $b) => $b['modified_timestamp'] <=> $a['modified_timestamp']);

        return $items;
    }

    /**
     * Safely delete a single media file and unlink any DB references
     */
    public function deleteMedia(string $relativePath): void
    {
        $cleanPath = ltrim(str_replace(['..', '\\'], ['', '/'], $relativePath), '/');
        $fullPath = public_path('uploads/'.$cleanPath);

        if (File::exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }

        AttendanceCorrection::where('foto_datang', $cleanPath)
            ->orWhere('foto_datang', 'uploads/'.$cleanPath)
            ->update(['foto_datang' => null]);

        AttendanceCorrection::where('foto_pulang', $cleanPath)
            ->orWhere('foto_pulang', 'uploads/'.$cleanPath)
            ->update(['foto_pulang' => null]);

        Setting::where('spesimen_ttd', $cleanPath)
            ->orWhere('spesimen_ttd', 'uploads/'.$cleanPath)
            ->update(['spesimen_ttd' => null]);

        Setting::where('spesimen_stempel', $cleanPath)
            ->orWhere('spesimen_stempel', 'uploads/'.$cleanPath)
            ->update(['spesimen_stempel' => null]);

        AttendanceReport::where('pdf_file_path', $cleanPath)
            ->orWhere('pdf_file_path', 'uploads/'.$cleanPath)
            ->update(['pdf_file_path' => null]);

        $this->selectedFiles = array_values(array_diff($this->selectedFiles, [$cleanPath]));

        if ($this->previewUrl && str_contains($this->previewUrl, $cleanPath)) {
            $this->closePreview();
        }

        Notification::make()
            ->success()
            ->title('Media Berhasil Dihapus')
            ->body("Berkas {$cleanPath} telah dihapus dengan aman dari sistem tanpa mempengaruhi fungsi lainnya.")
            ->send();
    }

    /**
     * Safely delete multiple selected media files
     */
    public function deleteSelected(): void
    {
        if (empty($this->selectedFiles)) {
            Notification::make()
                ->warning()
                ->title('Peringatan')
                ->body('Pilih minimal satu berkas media terlebih dahulu untuk dihapus.')
                ->send();

            return;
        }

        $count = count($this->selectedFiles);

        foreach ($this->selectedFiles as $relativePath) {
            $cleanPath = ltrim(str_replace(['..', '\\'], ['', '/'], $relativePath), '/');
            $fullPath = public_path('uploads/'.$cleanPath);

            if (File::exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath);
            }

            AttendanceCorrection::where('foto_datang', $cleanPath)
                ->orWhere('foto_datang', 'uploads/'.$cleanPath)
                ->update(['foto_datang' => null]);

            AttendanceCorrection::where('foto_pulang', $cleanPath)
                ->orWhere('foto_pulang', 'uploads/'.$cleanPath)
                ->update(['foto_pulang' => null]);

            Setting::where('spesimen_ttd', $cleanPath)
                ->orWhere('spesimen_ttd', 'uploads/'.$cleanPath)
                ->update(['spesimen_ttd' => null]);

            Setting::where('spesimen_stempel', $cleanPath)
                ->orWhere('spesimen_stempel', 'uploads/'.$cleanPath)
                ->update(['spesimen_stempel' => null]);

            AttendanceReport::where('pdf_file_path', $cleanPath)
                ->orWhere('pdf_file_path', 'uploads/'.$cleanPath)
                ->update(['pdf_file_path' => null]);
        }

        $this->selectedFiles = [];
        $this->selectAll = false;
        $this->closePreview();

        Notification::make()
            ->success()
            ->title('Penghapusan Berhasil')
            ->body("Sebanyak {$count} berkas media berhasil dihapus secara permanen dan aman.")
            ->send();
    }

    public function openPreview(string $url, string $type, string $title): void
    {
        $this->previewUrl = $url;
        $this->previewType = $type;
        $this->previewTitle = $title;
    }

    public function closePreview(): void
    {
        $this->previewUrl = null;
        $this->previewType = null;
        $this->previewTitle = null;
    }

    /**
     * Helper to format bytes to human readable format
     */
    protected function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    public function deleteMediaAction(): Action
    {
        return Action::make('deleteMedia')
            ->label('Hapus Berkas')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-trash')
            ->modalHeading('Hapus Berkas Media')
            ->modalDescription(function (array $arguments): string {
                $path = $arguments['path'] ?? '';
                $filename = basename($path);

                return "Apakah Anda yakin ingin menghapus berkas '{$filename}'? Berkas fisik akan dihapus permanen dari server dan data terkait akan dinolkan dengan aman tanpa merusak sistem.";
            })
            ->modalSubmitActionLabel('Ya, Hapus Berkas')
            ->modalCancelActionLabel('Batal')
            ->action(function (array $arguments): void {
                if (! empty($arguments['path'])) {
                    $this->deleteMedia($arguments['path']);
                }
            });
    }

    public function deleteSelectedAction(): Action
    {
        return Action::make('deleteSelected')
            ->label('Hapus Berkas Terpilih')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-trash')
            ->modalHeading('Hapus Berkas Terpilih')
            ->modalDescription(fn (): string => 'Apakah Anda yakin ingin menghapus sebanyak '.count($this->selectedFiles).' berkas media terpilih? Seluruh berkas fisik akan dihapus dan referensi data akan dinolkan secara otomatis dan aman.')
            ->modalSubmitActionLabel('Ya, Hapus Semua Terpilih')
            ->modalCancelActionLabel('Batal')
            ->action(function (): void {
                $this->deleteSelected();
            });
    }
}
