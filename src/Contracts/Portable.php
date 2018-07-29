<?php

namespace Soved\Laravel\Gdpr\Contracts;

interface Portable
{
    /**
     * Convert the model instance to a GDPR compliant data portability array.
     *
     * @return array
     */
    public function portable();

    /**
     * Get the GDPR compliant data portability array for the model.
     *
     * @return array
     */
    public function toPortableArray();
}
