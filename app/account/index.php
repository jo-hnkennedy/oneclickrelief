<?php

include_once '../include/config.php';
include_once '../include/session.php';

header('Location: '. ( ( !$user ) ? 'login' : 'home' ) . '.php');

?>