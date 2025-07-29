<?php

require_once __DIR__ . "/../api_utils.php";

require_auth();
success([MESSAGE => "You are authorized!"]);
