<?php

namespace Soved\Laravel\Gdpr;

use Carbon\Carbon;

trait Retentionable
{
    /**
     * Get the user's last activity.
     *
     * @return \Illuminate\Support\Carbon
     */
    public function getLastActivityAttribute(): Carbon
    {
        return $this->updated_at;
    }
}
