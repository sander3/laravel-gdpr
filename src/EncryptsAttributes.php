<?php

namespace Dialect\Gdpr;

trait EncryptsAttributes
{
    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        if (in_array($key, $this->encrypted) &&
            ! is_null($value)) {
            return decrypt($value);
        }

        return $value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute(
        $key,
        $value
    ) {
        if (in_array($key, $this->encrypted) &&
            ! is_null($value)) {
            $value = encrypt($value);
        }

        parent::setAttribute($key, $value);
    }
}
