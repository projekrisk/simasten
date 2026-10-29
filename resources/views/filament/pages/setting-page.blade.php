<x-filament-panels::page>
    <style>
        .simasten-settings-form {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .simasten-settings-actions {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .simasten-btn-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 22px;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2);
            transition: all 0.15s ease-in-out;
            white-space: nowrap;
        }
        .simasten-btn-save:hover {
            background-color: #1d4ed8;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.3);
        }
        .simasten-btn-save:active {
            transform: scale(0.98);
        }
        .simasten-btn-save:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        @media (max-width: 639px) {
            .simasten-settings-actions {
                margin-top: 20px;
            }
            .simasten-btn-save {
                width: 100%;
            }
        }
    </style>

    <form wire:submit="save" class="simasten-settings-form">
        {{ $this->form }}

        <div class="simasten-settings-actions">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="simasten-btn-save"
            >
                <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</x-filament-panels::page>
