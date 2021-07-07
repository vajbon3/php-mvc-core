<?php


namespace vajbon3\mvc\exceptions;


class NotFoundException extends \Exception
{
    protected $message = "This page could not be found";
    protected $code = 404;
}