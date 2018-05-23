<?php

namespace Dialect\Gdpr;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

trait Anonymizable
{
    /**
     * Update the model with anonymized data.
     *
     * @return array
     */
    public function anonymize($modelChecker = [])
    {
        $updateArray = [];

        // Only anonymize the fields specified
        if ($this->gdprAnonymizableFields !== null) {
            foreach ($this->gdprAnonymizableFields as $key => $val) {
                if (method_exists($this, 'getAnonymized'.Str::studly($val))) {
                    $updateArray[$val] = $this->{'getAnonymized'.Str::studly($val)}();
                } else {
                    if (\is_int($key)) {
                        $updateArray[$val] = $this->parseValue($val);
                    } else {
                        $updateArray[$key] = $this->parseValue($val);
                    }
                }
            }
        }

        // Update this model
        if (\count($updateArray)) {
            $this->update($updateArray);
        }

        // Eager load the given relations
        if ($this->gdprWith !== null) {
            $this->loadMissing($this->gdprWith);
            // Recursively update all related models
            foreach ($this->getRelations() as $relationName => $collection) {
                if (! array_key_exists($relationName, $modelChecker)) {
                    array_push($modelChecker, $relationName);
                    if (! ($collection instanceof Collection)) {
                        $collection = [$collection];
                    }
                    foreach ($collection as $item) {
                        $item->anonymize($modelChecker);
                    }
                }
            }
        }
    }

    /**
     * @param null $item
     *
     * @return mixed|null
     */
    public function parseValue($item = null)
    {
        if ($item instanceof \Closure) {
            $value = \call_user_func($item());
        } elseif ($item == null) {
            $value = $item;
        } else {
            $value = config('gdpr.string.default');
        }

        return $value;
    }

    /**
     * Get the GDPR compliant data anonymizable array for the model.
     *
     * @return array
     */
    public function toAnonymizableArray()
    {
        return $this->toArray();
    }
}
