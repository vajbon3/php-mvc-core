<?php


namespace app\core\exceptions;


class NotFoundException extends \Exception
{
    protected $message = "This page could not be found";
    protected $code = 404;
}