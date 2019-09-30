<?php

namespace App\Repositories\Contracts;

interface OrganizationRepositoryInterface
{
    /**
     * Method to update the currency of the org
     *
     * @param integer $id Org id
     * @param \App\Currency $currency
     *
     * @return boolean
     */
    public function updateCurrency($id, $currency);

    /**
     * Method to update the country of the org
     *
     * @param integer $id Org id
     * @param \App\Country $country
     *
     * @return boolean
     */
    public function updateCountry($id, $country);

    /**
     * Method to update/upload an image for the organiation. The following image fields
     * are supported as of now
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
    public function updateImage($id, $uploadedFile, $field);

    /**
     * Method to update system_donor_questions (json)
     *
     * @param integer $id Organization id
     * @param array $questions Questions with mailing_address and comment keys
     *
     * @return void
     */
    public function updateSystemDonorQuestions($id, $questions);

    /**
     * Method to deactivate the org
     *
     * @param integer $id Org id
     * @param array $data
     *
     * @return boolean
     */
    public function deactivate($id, $data);
}
