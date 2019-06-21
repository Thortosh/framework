<?php
namespace App\Models;

use Anton\Database\Model;

class UserModel extends Model {
    protected static $tablename = 'users';
    protected $hidden = [
        'password' //это поле нельзя хранить в сессии
    ];
}