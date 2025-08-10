<?php

namespace puzzlethings\src\gateway\interfaces;

use PDOException;

interface IGatewayWithFilters extends IGateway
{
    public function filtersToSQL(array $filters = []): string;
}