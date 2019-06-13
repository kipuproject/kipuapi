<?php
$recordStatus = [1 => 'ACTIVO',  
                 0 => 'INACTIVO'];

$bookingStatus = [2 => 'CONFIRMADA',  
                              3 => 'CANCELADA',
                              5 => '-',
                              1 => '-',
                              4 => '-',
                              6 => 'PENDIENTE'];

return [
    'recordStatus'  => $recordStatus,
    'bookingStatus' => $bookingStatus
];