<?php


namespace App\Library\Validator\Request;


class HttpStatusApi
{
    public const VALIDATION_ERROR = 422;
    public const INTERNAL_SERVER_ERROR = 500;
    public const SUCCESS = 200;
    public const USER_NOT_FOUND = 404;
}