<?php

namespace Philipretl\TechnicalTestSourcetoad\Database\Contracts;

use PDO;
use Philipretl\TechnicalTestSourcetoad\Database\SQliteConnection;

interface DataBaseConnection
{
    public static function connect(string $database_path): SQliteConnection;
}