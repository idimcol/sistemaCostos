<?php

namespace App\Enums;

enum TipoPago: string {
    case Mensual = 'mensual';
    case Quincenal = 'quincenal';
    case Semanal = 'semanal';
    case Diario = 'diario';
    case Hora = 'hora';
}
