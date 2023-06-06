<?php

final class GoBackMiddleware implements Middleware
{
    public function __invoke($req)
    {
        $descriptor = $req->getDescriptor();
        $segments = explode('@',$descriptor);

        if(!empty($segments[0]) && !empty($segments[1]))
            $goBackUrl = ProtocolConfig::getProtocol() . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $segments[0] . '/' . 'index';
        else
            $goBackUrl = ProtocolConfig::getProtocol() . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

        
        $_SESSION['prev_url'] = $goBackUrl;


        return $req;
    }
}