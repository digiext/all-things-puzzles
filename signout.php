<?php
require_once "util/function.php";

unset($_SESSION[USER_ID]);
unset($_SESSION[USER_GROUP_ID]);

header("Location: index.php");
