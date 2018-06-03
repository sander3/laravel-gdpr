<?php

namespace Soved\Laravel\Gdpr;

trait Retentionable
{
    /**
     * Get the user's last activity.
     *
     * @return \Illuminate\Support\Carbon
     */
    public function getLastActivityAttribute()
    {
        return $this->updated_at;
    }
}
