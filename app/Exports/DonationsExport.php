<?php

namespace App\Exports;

use DB;
use App\Donation;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Repositories\Contracts\DonationRepositoryInterface;

class DonationsExport implements FromCollection, WithHeadings, WithMapping
{

    /**
     * Organization.
     * @var App\Organization
     */
    public $organization;

    private $questionPosition = [];

    private $repo;

    /**
     * Create a new controller instance.
     * @param  App\Organization $organization
     * @param  App\Repositories\Contracts\DonationRepositoryInterface $repo
     * @return void
     */
    public function __construct(
        $organization,
        DonationRepositoryInterface $repo
    ) {
        $this->organization = $organization;
        $this->repo = $repo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->repo->getDonationsList($this->organization->id, null, false);
    }

    /**
     * Function to display headings
     */
    public function headings(): array
    {
        $excelHeader =  [
            __('Name'),
            __('Donation'),
            __('Net'),
            __('Campaign'),
            __('Reward'),
            __('Fund'),
            __('Time'),
        ];

        $pos = sizeof($excelHeader);
        foreach ($this->organization->donorQuestions as $question) {
            array_push($excelHeader, $question->question);
            $this->questionPosition[$question->id] = $pos;
            $pos++;
        }
        return $excelHeader;
    }

    public function map($donation): array
    {
        $donationId = $donation->id;
        $questions = $this->getQuestions($donationId);
        if (empty($donation->net_amount)) {
            $donation->net_amount = '0.00';
        }
        $excelBody = [
            $donation->full_name,
            $donation->symbol.$donation->gross_amount,
            $donation->symbol.$donation->net_amount,
            $donation->name,
            $donation->title,
            ucfirst($donation->entry_type) . ': '. ((ucfirst($donation->entry_type) == 'Online') ? ucfirst($donation->card_brand) : ucfirst($donation->donation_method)),
            Carbon::parse($donation->created_at)->format('n/j/Y g:i A')
        ];
        foreach ($questions as $answer) {
            $pos = $this->questionPosition[$answer->id];
            $excelBody[$pos] = $answer->answer;
        }

        return $excelBody;
    }

    /**
     * Method to get question and answers related to a particular donation
     * @param donationId
     * @return array
     */
    private function getQuestions($donationId)
    {
        $questions = DB::table('donor_questions')
                    ->leftJoin('donation_question_answers', function($join) use($donationId) {
                        $join->on('donor_questions.id', '=', 'donation_question_answers.donor_question_id');
                        $join->on('donation_question_answers.donation_id', '=', DB::raw($donationId));
                    })
                    ->select(
                        'donor_questions.id',
                        'donor_questions.question',
                        DB::raw('ifnull(donation_question_answers.answer, "--") as answer'),
                        DB::raw('ifnull(donation_question_answers.donation_id,0) as donation_id'),
                        'donation_question_answers.donor_question_id')
                    ->where('donor_questions.organization_id', '=', $this->organization->id)
                    ->whereNull('donor_questions.deleted_at')
                    ->whereNull('donation_question_answers.deleted_at')
                    ->orderBy('donor_questions.created_at', 'asc')
                    ->get();

        return $questions;
    }
}
