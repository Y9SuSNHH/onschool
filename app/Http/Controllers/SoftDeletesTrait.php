<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait SoftDeletesTrait
 * @package App\Traits
 */
trait SoftDeletesTrait
{

    use SoftDeletes;

    /**
     * Perform the actual delete query on this model instance.
     * Parent method in softDeletes trait
     * @return void
     */
    protected function runSoftDelete(): void
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];

        $this->{$this->getDeletedAtColumn()} = $time;

        if ($this->timestamps && !is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }
        $columns['deleted_by'] = auth()->user()->id;

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

        $this->fireModelEvent('trashed', false);
    }
}
