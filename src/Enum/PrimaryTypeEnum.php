<?php

namespace Blugen\Enum;

use Kongulov\Traits\InteractWithEnum;

enum PrimaryTypeEnum: string
{
    use InteractWithEnum;

    case PROCEDURE = 'procedure';
    case QUERY = 'query';
    case RECORD = 'record';
    case SUBSCRIPTION = 'subscription';
}
