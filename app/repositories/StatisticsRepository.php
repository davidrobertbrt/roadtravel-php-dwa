<?php

require_once '../app/model/HitReport.php';

final class StatisticsRepository
{
    public static $stats;

    private function __construct() {}

    public static function generate()
    {
        $conn = DatabaseConnection::getConnection();

        $sql = "SELECT COUNT(*) AS total_hits FROM hits";
        $statement = $conn->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $totalHits = $row['total_hits'];

        $sql = "SELECT COUNT(DISTINCT ip_address) AS unique_visitors FROM hits";
        $statement = $conn->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $uniqueVisitors = $row['unique_visitors'];

        $meanHitsPerVisitor = $totalHits / $uniqueVisitors;

        $sql = "SELECT DATE(timestamp) AS date, COUNT(*) AS hits_per_day FROM hits GROUP BY DATE(timestamp)";
        $statement = $conn->query($sql);
        $hitsPerDay = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $hitsPerDay[$row['date']] = $row['hits_per_day'];
        }

        $maxHitsPerDay = max($hitsPerDay);
        $averageHitsPerDay = array_sum($hitsPerDay) / count($hitsPerDay);

        $variance = 0;
        foreach ($hitsPerDay as $hits) {
            $variance += pow($hits - $averageHitsPerDay, 2);
        }
        $variance /= count($hitsPerDay);
        $stdDevHitsPerDay = sqrt($variance);

        self::$stats = array(
            'totalHits' => $totalHits,
            'uniqueVisitors' => $uniqueVisitors,
            'meanHitsPerVisitor' => $meanHitsPerVisitor,
            'maxHitsPerDay' => $maxHitsPerDay,
            'averageHitsPerDay' => $averageHitsPerDay,
            'stdDevHitsPerDay' => $stdDevHitsPerDay,
            'hitsPerDay' => $hitsPerDay
        );

        
        return true;
    }

}
