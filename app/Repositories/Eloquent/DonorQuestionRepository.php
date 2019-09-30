<?php

namespace App\Repositories\Eloquent;

use DB;
use App\Repositories\Contracts\DonorQuestionRepositoryInterface;

class DonorQuestionRepository extends Repository implements DonorQuestionRepositoryInterface
{
    /**
     * Get the max sort order of donor questions for the given organization
     *
     * @param integer $organizationId
     *
     * @return integer
     */
    public function getMaxSortOrder($organizationId)
    {
        return $this->model->where(['organization_id' => $organizationId])
            ->max('sort_order');
    }

    /**
     * Get the donor questions for last donation made by donor
     *
     * @param integer $organizationId
     * @param integer $donationId
     *
     * @return integer
     */
    public function getDonorQuestionAnswers($organizationId, $donationId)
    {
        return $this->model->where('donor_questions.organization_id', $organizationId)
            //->join('donation_question_answers', 'donor_questions.id', '=', 'donation_question_answers.donor_question_id')
            //->where('donation_question_answers.donation_id', $donationId)
            ->leftJoin('donation_question_answers', function($join) use($donationId) {
                $join->on('donor_questions.id', '=', 'donation_question_answers.donor_question_id');
                $join->on('donation_question_answers.donation_id', '=', DB::raw($donationId));
            })
            ->whereNull('donor_questions.deleted_at')
            ->whereNull('donation_question_answers.deleted_at')
            ->select('donor_questions.id', 'donor_questions.question', 'donation_question_answers.answer', 'donation_question_answers.donation_id', 'donation_question_answers.donor_question_id')
            ->orderBy('donor_questions.sort_order')
            ->orderBy('donor_questions.id')
            ->get();
    }

}
