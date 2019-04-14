<?php

namespace Soved\Laravel\Gdpr;

use Soved\Laravel\Gdpr\Contracts\Portable as PortableContract;

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
            $this->loadRelations($this->gdprWith);
        }

        // Make the given attributes visible
        if (isset($this->gdprVisible)) {
            $this->setVisible($this->gdprVisible);
        }

        // Make the given attributes hidden
        if (isset($this->gdprHidden)) {
            $this->setHidden($this->gdprHidden);
        }

        return $this->toPortableArray();
    }

    /**
     * Eager load the given relations.
     *
     * @param  array  $relations
     * @return void
     */
    public function loadRelations(array $relations)
    {
        $portableRelations = $this->getPortableRelations($relations);

        array_walk($portableRelations, [$this, 'loadPortableRelation']);

        $this->load(array_diff($relations, $portableRelations));
    }

    /**
     * Get all portable relations.
     *
     * @param  array  $relations
     * @return array
     */
    private function getPortableRelations(array $relations)
    {
        $portableRelations = [];

        foreach ($relations as $relation) {
            if ($this->$relation()->getRelated() instanceof PortableContract) {
                $portableRelations[] = $relation;
            }
        }

        return $portableRelations;
    }

    /**
     * Load and transform a portable relation.
     *
     * @param  string  $relation
     * @return void
     */
    private function loadPortableRelation(string $relation)
    {
        $instance = $this->$relation();

        $collection = $instance
            ->get()
            ->transform(function ($item) {
                return $item->portable();
            });

        $class = class_basename(get_class($instance));

        if (in_array($class, ['HasOne', 'BelongsTo'])) {
            $collection = $collection->first();
        }

        $this->attributes[$relation] = $collection;
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
