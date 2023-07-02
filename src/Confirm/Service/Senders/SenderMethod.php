<?php

namespace Confirm\Service\Senders;

enum SenderMethod: string
{
    case SMS = 'sms';
    case EMAIL = 'email';
    case TELEGRAM = 'telegram';
}
