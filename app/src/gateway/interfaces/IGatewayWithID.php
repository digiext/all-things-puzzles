<?php
namespace puzzlethings\src\gateway\interfaces;

/**
 * @template T
 */
interface IGatewayWithID extends IGateway
{
    /**
     * @param int $id
     * @return ?T
     */
    public function findById(int $id): mixed;

    /**
     * @param int $id
     * @return bool Successfully deleted or not
     */
    public function delete(int $id): bool;
}