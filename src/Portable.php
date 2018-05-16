<?php

namespace Dialect\Gdpr;

trait Portable
{
    /**
     * Convert the model instance to a GDPR compliant data portability array.
     *
     * @return array
     */
    public function portable()
    {
        // Eager load the given relations
        if (isset($this->gdprWith)) {
            $this->loadMissing($this->gdprWith);
        }

        // Make the given attributes visible
        if (isset($this->gdprVisible)) {
            $this->makeVisible($this->gdprVisible);
        }

        // Make the given attributes hidden
        if (isset($this->gdprHidden)) {
            $this->makeHidden($this->gdprHidden);
        }

        return $this->toPortableArray();
    }

    /**
     * Get the GDPR compliant data portability array for the model.
     *
     * @return array
     */
    public function toPortableArray()
    {
        return $this->toArray();
    }
}
