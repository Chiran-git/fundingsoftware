<?php

namespace App\Repositories\Contracts;

interface DonorQuestionRepositoryInterface
{
    /**
     * Get the max sort order of donor questions for the given organization
     *
     * @param integer $organizationId
     *
     * @return integer
     */
    public function getMaxSortOrder($organizationId);
}
