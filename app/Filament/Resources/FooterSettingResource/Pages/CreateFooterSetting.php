<?php

namespace App\Filament\Resources\FooterSettingResource\Pages;

use App\Filament\Resources\FooterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFooterSetting extends CreateRecord
{
    protected static string $resource = FooterSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default copyright if not provided
        if (empty($data['copyright_en'])) {
            $data['copyright_en'] = '© 2025 V U S Entreprenad & Bemanning AB - Org.nr 559540-8963';
        }
        if (empty($data['copyright_sv'])) {
            $data['copyright_sv'] = '© 2025 V U S Entreprenad & Bemanning AB - Org.nr 559540-8963';
        }
        
        // Set default quick links if not provided
        if (empty($data['quick_links'])) {
            $data['quick_links'] = [
                [
                    'label_en' => 'For Employers',
                    'label_sv' => 'För arbetsgivare',
                    'route' => 'for-employers',
                    'custom_url' => null,
                ],
                [
                    'label_en' => 'Our Services',
                    'label_sv' => 'Våra tjänster',
                    'route' => 'for-employers',
                    'custom_url' => null,
                ],
                [
                    'label_en' => 'Contact',
                    'label_sv' => 'Kontakt',
                    'route' => 'contact',
                    'custom_url' => null,
                ],
                [
                    'label_en' => 'Privacy Policy',
                    'label_sv' => 'Integritetspolicy',
                    'route' => 'privacy',
                    'custom_url' => null,
                ],
            ];
        }

        return $data;
    }
}
