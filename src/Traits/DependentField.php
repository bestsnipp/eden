<?php

namespace Dgharami\Eden\Traits;

trait DependentField
{

    protected $targets = [];

    protected $hasDependency = false;

    /**
     * Get all depends on targets
     *
     * @return array
     */
    public function getDependentTargets()
    {
        return $this->targets;
    }

    /**
     * Check and set field($hasDependency) property has any dependency or not
     *
     * @param array $fields
     * @return $this
     */
    public function isDependent(array $fields)
    {
        $this->hasDependency = in_array($this->key, $fields);
        return $this;
    }

    /**
     * Set field dependency targets
     *
     * @param array|string $targets
     * @return $this
     */
    public function dependsOn($targets)
    {
        if (is_array($targets)) {
            $this->targets = collect(appCall($targets))->toArray();
        } else {
            $this->targets = explode(' ', collect(appCall($targets))->join('_'));
        }
        return $this;
    }

}
