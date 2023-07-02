<?php

namespace Core\Orm;

enum Events
{
    case CREATE_BEFORE;
    case CREATE_AFTER;
    case UPDATE_BEFORE;
    case UPDATE_AFTER;
    case DELETE_BEFORE;
    case DELETE_AFTER;
}
