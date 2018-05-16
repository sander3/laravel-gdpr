<?php

namespace Soved\Laravel\Gdpr\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class GdprDownloaded
{
    use SerializesModels;

    /**
     * @var \App\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
