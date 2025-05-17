<?php

namespace Blugen\Enum;

enum SupportTypeEnum: string
{
    case INPUT = 'input';
    case OUTPUT = 'output';
    case ERRORS = 'errors';
    case MESSAGE = 'message';
}
