<?php
function obtenerLimiteLocalesPorPlan($plan) {
    switch ($plan) {
        case 'basico':
            return 3;
        case 'premium':
            return 1000; // puedes usar un número alto para ilimitado
        case 'gratis':
        default:
            return 1;
    }
}
