<?php


namespace vajbon3\mvc;


use vajbon3\mvc\db\DBModel;

abstract class UserModel extends DBModel
{
    abstract public function getDisplayName(): string;
}