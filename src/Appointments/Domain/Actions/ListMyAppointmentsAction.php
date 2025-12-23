<?php

declare(strict_types=1);

namespace Lightit\Appointments\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Appointments\Domain\Models\Appointment;
use Lightit\Users\Domain\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class ListMyAppointmentsAction
{
    /**
     * @return LengthAwarePaginator<int, Appointment>
     */
    public function execute(User $user): LengthAwarePaginator
    {
        return QueryBuilder::for(
            Appointment::query()->whereBelongsTo($user)
        )
            ->orderByDesc('id')
            ->paginate();
    }
}
