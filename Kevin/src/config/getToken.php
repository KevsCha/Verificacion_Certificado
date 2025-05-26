<?php 
function getToken() {
    return json_decode(file_get_contents(__DIR__ . '/token.json'), true);
}