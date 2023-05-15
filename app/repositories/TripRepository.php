<?php

class TripRepository
{
    // utility class
    private function construct() {}

    public static function getTableName() {return 'trips';}

    public static function readById($id) {}

    public static function readByBusId($busId) {}

    public static function fetch($locationStartId, $locationEndId,$dateTimeStart,$dateTimeEnd) {}

    public static function readAll() {}

    public static function create(&$trip) {}

    public static function update(&$trip) {}

    public static function delete(&$trip) {}
}