<?php

namespace App\Enums;

enum FilterStrEnum: string
{
    case DESC = 'desc';
    case ASC = 'asc';
    case START_DATE = 'start_date';
    case END_DATE = 'end_date';
}
