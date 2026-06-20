<?php

namespace App\Enums;

enum ServiceType: string
{
    case UmkmWebsite = 'umkm_website';
    case CompanyProfile = 'company_profile';
    case Ecommerce = 'ecommerce';
    case School = 'school';
    case Government = 'government';
    case CustomSoftware = 'custom_software';
    case MobileApp = 'mobile_app';

    public function label(): string
    {
        return match ($this) {
            self::UmkmWebsite => 'Website UMKM',
            self::CompanyProfile => 'Company Profile',
            self::Ecommerce => 'E-commerce',
            self::School => 'Website Sekolah',
            self::Government => 'Instansi / Pemerintah',
            self::CustomSoftware => 'Software Custom',
            self::MobileApp => 'Aplikasi Mobile',
        };
    }
}
