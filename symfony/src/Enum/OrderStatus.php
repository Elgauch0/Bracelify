<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case CANCELLED = 'CANCELLED';
    case REFUNDED = 'REFUNDED';
    case SHIPPED = 'SHIPPED';

    
    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::PAID => 'Payée',
            self::CANCELLED => 'Annulée',
            self::REFUNDED => 'Remboursée',
            self::SHIPPED => 'Expédiée',
        };
    }
}