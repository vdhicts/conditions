<?php

namespace Vdhicts\Conditions\Enums;

enum ConditionLevel: string
{
    case Success = 'success';
    case Warning = 'warning';
    case Info = 'info';
    case Error = 'error';
}
