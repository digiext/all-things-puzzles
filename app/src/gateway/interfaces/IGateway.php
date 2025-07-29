<?php

namespace puzzlethings\src\gateway\interfaces;

use PDOException;

/**
 * @template T
 */
interface IGateway
{
    public function count(mixed $options = []): int;

    /**
     * @param array $options
     * @param bool $verbose
     * @return T[]|PDOException|null
     */
    public function findAll(array $options = [], bool $verbose = false): array|null|PDOException;
}