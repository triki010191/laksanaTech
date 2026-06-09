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
}
