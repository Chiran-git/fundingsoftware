<?php

namespace App\Presenters;

class OrganizationPresenter extends Presenter
{
    /**
     * Status of the organization
     *
     * @return string Either setup_needed, disabled or active
     */
    public function status()
    {
        if (! $this->entity->setup_completed) {
            return 'setup_needed';
        }

        return 'active';
    }
}
