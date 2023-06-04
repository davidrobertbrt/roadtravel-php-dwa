<?php

final class HitMiddleware implements Middleware
{
    public function __invoke($req)
    {
        if(!isset($_SESSION['visit']))
        {
            $_SESSION['visit'] = true;
            $ip = $_SERVER['REMOTE_ADDR'];

            $sql = "INSERT INTO hits(ip_address,timestamp) VALUES('$ip',NOW())";
            $conn = DatabaseConnection::getConnection();

            if($conn->query($sql) === false)
            {
                $req = new Response("Error registering hit" . $conn->error,500);
                return $req;
            }
        }

        return $req;
    }
}