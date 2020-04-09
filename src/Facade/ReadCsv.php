<?php
namespace Ikbal\Datareader\Facade;

use Illuminate\Support\Facades\Facade;

class ReadCsv extends Facade {
    protected static function getFacadeAccessor() { return 'readcsv'; }
}