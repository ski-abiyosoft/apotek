<?php

function generate_fixed_asset_code (string $auto_increment): string
{
    switch (strlen($auto_increment)) {
        case 1:
            return 'AK0000000' . $auto_increment;
            break;
        case 2:
            return 'AK000000' . $auto_increment;
        case 3:
            return 'AK00000' . $auto_increment;
        case 4:
            return 'AK0000' . $auto_increment;
        case 5:
            return 'AK000' . $auto_increment;
        case 6:
            return 'AK00' . $auto_increment;
        case 7:
            return 'AK0' . $auto_increment;
        default:
            return 'AK' . $auto_increment;
            break;
    }
}

function generate_fix_group_code (string $auto_increment): string
{
    switch (strlen($auto_increment)) {
        case 1:
            return 'AK00' . $auto_increment;
            break;
        case 2:
            return 'AK0' . $auto_increment;
            break;
        default:
            return 'AK' . $auto_increment;
            break;
    }
}