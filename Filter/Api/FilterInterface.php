<?php
namespace Riyas\Filter\Api;

interface FilterInterface {
    /**
     *
     * @api
     * @param int $categoryId
     * @return $this
     */
    public function getActiveFilters($categoryId);
}
