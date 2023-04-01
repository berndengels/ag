<?php

namespace App\Libs\Crawler;

use App\Models\Location;

interface ICrawler
{
    public function setLocation(Location $location):self;
    public function run():self;
    public function update():self;
    public function getEntity():object|null;
}
