<?php
require_once "util/function.php";

deleteCookie(LOGGED_IN);
deleteCookie(REMEMBER_ME);
deleteCookie(USER_GROUP);

header("Location: index.php");
