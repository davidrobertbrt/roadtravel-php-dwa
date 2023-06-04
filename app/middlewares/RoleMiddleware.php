<?php

class RoleMiddleware implements Middleware
{
    public function __invoke($request)
    {
        $roleRules = require_once '../app/config/RoleRules.php';


        $descriptor = $request->getDescriptor();

        if($descriptor === '@')
            $controller = '@';
        else
        {
            $controllerAction = explode('@', $descriptor);
            $controller = $controllerAction[0];
        }

        $role = $_SESSION['role'] ?? 'default';
        $allowedActions = $roleRules[$role] ?? [];

        if (in_array('*', $allowedActions) || in_array($controller, $allowedActions)) {
            // Access granted
            return $request;
        } else {
            // Access denied
            $response = new Response("Access denied",403);
            return $response;
        }
    }
}
