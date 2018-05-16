<?php

namespace Dialect\Gdpr;

trait Anonymizable
{
    /**
     * Update the model with anonymized data.
     *
     * @return array
     */
    public function anonymize()
    {
        $updateArray = [];
        $tmpArr = [];

        // Eager load the given relations
        if (isset($this->gdprWith)) {
            $this->loadMissing($this->gdprWith);

            foreach ($this->relations as $key => $val) {
                if (isset($this->$key->gdprAnonymizableFields)) {
                    foreach ($this->$key->gdprAnonymizableFields as $relationKey => $relationVal) {
                        $tmpArr[$relationKey] = $this->parseValue($relationVal);
                    }
                    $updateArray[$key] = $tmpArr;
                }
            }
        }

        // Only anonymize the fields specified
        if (isset($this->gdprAnonymizableFields)) {
            foreach ($this->gdprAnonymizableFields as $key => $val) {
                $updateArray[$key] = $this->parseValue($val);
            }
        }

        $this->update($updateArray);

        return $this->toAnonymizableArray();
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
        } elseif ($item) {
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
