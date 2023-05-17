<?php
/**
 * This module parses the request URL and returns an associative array
 * containing the controller, action, HTTP method, and request data (GET and POST).
 * The URL is split into an array using the forward slash '/' as a delimiter. The
 * first and second elements of this array are assigned to the $controller and $action
 * variables respectively. The HTTP method is obtained from the $_SERVER['REQUEST_METHOD']
 * superglobal variable.
 * The request data is stored in the $requestData array, with GET parameters stored in
 * the 'GET' key and POST parameters stored in the 'POST' key. The GET parameters are obtained
 * by taking all elements of the URL array after the first two. The POST parameters are obtained
 * from the $_POST superglobal variable if the HTTP method is POST.
 * The function returns an associative array with the following keys:
 * - descriptor: a string in the format "controller@action"
 * - method: the HTTP method
 * - data: an associative array containing the GET and POST parameters
 * @return array An associative array containing parsed information about the request.
*/

class Request{
    private $descriptor;
    private $method;
    private $data;

    public function __construct($descriptor,$method,$data)
    {
        $this->descriptor = $descriptor;
        $this->method = $method;
        $this->data = $data;
    }

    public static function parse()
    {
        $uri = array();

        if(isset($_GET['url']))
            $uri = explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
        
        $controller = $uri[0] ?? null;
        $action = $uri[1] ?? null;
        $method = $_SERVER['REQUEST_METHOD'];

        if($method === 'POST')
            $requestData = $_POST;
        else
            $requestData = (count($uri) > 2) ? array_values(array_slice($uri,2)) : null;
    
        return new self($controller . '@' . $action, $method, $requestData);
    }

    public function getDescriptor() {
        return $this->descriptor;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data){
        $this->data = $data;
    }
}
