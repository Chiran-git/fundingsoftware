<?php

namespace App\Repositories\Eloquent;

use DB;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class OrganizationRepository extends Repository implements OrganizationRepositoryInterface
{
    /**
     * Method to update the currency of the org
     *
     * @param integer $id Org id
     * @param \App\Currency $currency
     *
     * @return boolean
     */
    public function updateCurrency($id, $currency)
    {
        // If we have the associated model instance
        if ($currency) {
            return $this->find($id)->currency()->associate($currency)->save();
        }

        return false;
    }

    /**
     * Method to update the country of the org
     *
     * @param integer $id Org id
     * @param \App\Country $country
     *
     * @return boolean
     */
    public function updateCountry($id, $country)
    {
        // If we have the associated model instance
        if ($country) {
            return $this->find($id)->country()->associate($country)->save();
        }

        return false;
    }

    /**
     * Method to update/upload an image for the organiation.
     * The following image fields are supported as of now
     * - cover_image
     * - logo
     * - appeal_photo
     *
     * @param integer $id
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param string $field
     *
     * @return void
     */
    public function updateImage($id, $uploadedFile, $field)
    {
        if (! in_array($field, ['cover_image', 'logo', 'appeal_photo'])) {
            return false;
        }

        $uploadPath = '/organizations/' . $id;

        $options = ['disk' => 'public_uploads', 'visibility' => 'public'];

        // Find the old image (if any) which will need to be deleted
        $oldImage = $this->find($id)->{$field};

        // Upload the image using "uploads" disk (s3)
        if ($imagePath = $this->uploadImage($uploadedFile, $uploadPath, $options)) {

            // Update the image fields in the organization.
            $updated = $this->update(
                $id,
                [
                    $field => $imagePath,
                    "{$field}_filename" => $uploadedFile->getClientOriginalName(),
                    "{$field}_filesize" => $uploadedFile->getFileInfo()->getSize(),
                ]
            );

            if ($updated && $oldImage) {

                return $this->deleteImageFromStorage($options['disk'], $oldImage);
            }

            return $updated;
        }

        return false;
    }

    /**
     * Method to update system_donor_questions (json)
     *
     * @param integer $id Organization id
     * @param array $questions Questions with mailing_address and comment keys
     *
     * @return void
     */
    public function updateSystemDonorQuestions($id, $questions)
    {
        // Prepare the json array
        $questionsToSave = [
            'mailing_address' => [
                'required' => isset($questions['mailing_address']['required'])
                    && $questions['mailing_address']['required'] == true,
                'enabled' => isset($questions['mailing_address']['enabled'])
                    && $questions['mailing_address']['enabled'] == true,
            ],
            'comment' => [
                'required' => isset($questions['comment']['required'])
                    && $questions['comment']['required'] == true,
                'enabled' => isset($questions['comment']['enabled'])
                    && $questions['comment']['enabled'] == true,
            ]
        ];

        return $this->update($id, ['system_donor_questions' => json_encode($questionsToSave)]);
    }

    /**
     * Method to deactivate the org
     *
     * @param integer $id Org id
     * @param array $data
     *
     * @return boolean
     */
    public function deactivate($id, $data)
    {
        if ($data) {
            return $this->update(
                $id,
                [
                    'deactivated_at' => $data['deactivated_at'],
                    'deactivated_by' => $data['deactivated_by']
                ]
            );
        }

        return false;
    }

    /**
     * Get the organizations list.
     *
     * @param integer $limit
     * @param boolean $pagination
     * @param array   $params
     *
     * @return array
     */
    public function getOrganizationsList($limit = 10, $pagination = true)
    {
        $sortBy = "";
        if (!empty(request()->query('sort'))) {
            $sortBy = json_decode(request()->query('sort'));
        }
        $query = $this->model
            ->leftJoin('donations', 'donations.organization_id', '=', 'organizations.id')
            ->leftJoin('currencies', 'currencies.id', '=', 'organizations.currency_id')
            ->select('organizations.id','organizations.name', 'organizations.logo', 'organizations.created_at', 'currencies.symbol',
                DB::raw("coalesce( (SELECT COUNT(campaigns.id) FROM campaigns
                    WHERE campaigns.organization_id = organizations.id
                    GROUP BY organizations.id), 0) as no_of_campaigns"),
                DB::raw('ifnull(sum(donations.gross_amount),0) as total_donations'),
                DB::raw('ifnull(sum(donations.net_amount),0) as net_donations')
            )
            ->whereNull('organizations.deactivated_at')
            ->groupBy('organizations.id')
            ->when($sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy->fieldName, $sortBy->order);
            }, function ($query) {
                return $query->orderBy('organizations.created_at', 'desc');
            });

        if ($pagination) {
            return $query->paginate($limit);
        } else {
            $query->orderBy('organizations.created_at', 'desc');
            return $query->get();
        }
    }
}
