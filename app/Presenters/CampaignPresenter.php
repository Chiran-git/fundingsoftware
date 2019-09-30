<?php

namespace App\Presenters;

class CampaignPresenter extends Presenter
{
    /**
     * Status of the organization
     *
     * @return string Either disabled, unpublished, inactive or active
     */
    public function status()
    {
        if ($this->entity->disabled_at) {
            return 'disabled';
        }

        if (! $this->entity->published_at) {
            return 'unpublished';
        }

        if (! $this->entity->isActive()) {
            return 'inactive';
        }

        return 'active';
    }
}
