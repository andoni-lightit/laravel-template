<?php

declare(strict_types=1);

namespace Lightit\Clinics\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lightit\Doctors\Domain\Models\Doctor;

/**
 * Domain\Clinics\Models\Clinic
 *
 * @property int                             $id
 * @property string                          $name
 * @property string|null                     $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clinic whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Eloquent
 */
class Clinic extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsToMany<Doctor, $this>
     */
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class);
    }
}
