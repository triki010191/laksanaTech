<?php

namespace App\Enums;

enum WorkStage: string
{
    case Design = 'design';
    case Coding = 'coding';
    case Testing = 'testing';
    case Revision = 'revision';
    case Delivery = 'delivery';

    public function label(): string
    {
        return match ($this) {
            self::Design => 'Desain',
            self::Coding => 'Koding',
            self::Testing => 'Testing',
            self::Revision => 'Revisi',
            self::Delivery => 'Serah Terima',
        };
    }
}
