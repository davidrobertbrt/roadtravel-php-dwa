<?php
interface Middleware{
    public function __invoke($data);
}