<?php

namespace App\Enums;

enum RoleName: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case TransactionAdmin = 'transaction_admin';
    case Sales = 'sales';
    case Freelancer = 'freelancer';
    case Customer = 'customer';
}
