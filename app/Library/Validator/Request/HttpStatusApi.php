<?php


namespace App\Library\Validator\Request;


class HttpStatusApi
{
    public const VALIDATION_ERROR = 422;
    public const INTERNAL_SERVER_ERROR = 500;
    public const SUCCESS = 200;
    public const SUCCESS_CREATED = 201;
    public const SUCCESS_WITHOUT_ACTION = 200;
    public const USER_NOT_FOUND = 404;

    public const TRANSACTION_NOT_AUTHORIZED = 401;
}