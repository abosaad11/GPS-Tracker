<?php declare(strict_types=1);

namespace App\Domains\Refuel\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;
use App\Domains\Position\Model\Position as PositionModel;

class Refuel extends BuilderAbstract
{
    /**
     * @param int $city_id
     *
     * @return self
     */
    public function byCityId(int $city_id): self
    {
        return $this->whereIn('position_id', PositionModel::selectOnly('id')->byCityId($city_id));
    }

    /**
     * @param int $country_id
     *
     * @return self
     */
    public function byCountryId(int $country_id): self
    {
        return $this->whereIn('position_id', PositionModel::selectOnly('id')->byCountryId($country_id));
    }

    /**
     * @param string $date_at
     *
     * @return self
     */
    public function byDateAtAfter(string $date_at): self
    {
        return $this->whereDate('date_at', '>=', $date_at);
    }

    /**
     * @param string $date_at
     *
     * @return self
     */
    public function byDateAtBefore(string $date_at): self
    {
        return $this->whereDate('date_at', '<=', $date_at);
    }

    /**
     * @param int $state_id
     *
     * @return self
     */
    public function byStateId(int $state_id): self
    {
        return $this->whereIn('position_id', PositionModel::selectOnly('id')->byStateId($state_id));
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->orderByDateAtDesc();
    }

    /**
     * @return self
     */
    public function orderByDateAtDesc(): self
    {
        return $this->orderBy('date_at', 'DESC');
    }

    /**
     * @return self
     */
    public function orderByDateAtAsc(): self
    {
        return $this->orderBy('date_at', 'ASC');
    }

    /**
     * @param ?int $city_id
     *
     * @return self
     */
    public function whenCityId(?int $city_id): self
    {
        return $this->when($city_id, static fn ($q) => $q->byCityId($city_id));
    }

    /**
     * @param ?int $city_id
     * @param ?int $state_id
     * @param ?int $country_id
     *
     * @return self
     */
    public function whenCityStateCountryId(?int $city_id, ?int $state_id, ?int $country_id): self
    {
        if ($city_id) {
            return $this->byCityId($city_id);
        }

        if ($state_id) {
            return $this->byStateId($state_id);
        }

        if ($country_id) {
            return $this->byCountryId($country_id);
        }

        return $this;
    }

    /**
     * @param ?int $country_id
     *
     * @return self
     */
    public function whenCountryId(?int $country_id): self
    {
        return $this->when($country_id, static fn ($q) => $q->byCountryId($country_id));
    }

    /**
     * @param ?string $before_date_at
     * @param ?string $after_date_at
     *
     * @return self
     */
    public function whenDateAtBetween(?string $before_date_at, ?string $after_date_at): self
    {
        return $this->whenDateAtAfter($before_date_at)->whenDateAtBefore($after_date_at);
    }

    /**
     * @param ?string $date_at
     *
     * @return self
     */
    public function whenDateAtAfter(?string $date_at): self
    {
        return $this->when($date_at, static fn ($q) => $q->byDateAtAfter($date_at));
    }

    /**
     * @param ?string $date_at
     *
     * @return self
     */
    public function whenDateAtBefore(?string $date_at): self
    {
        return $this->when($date_at, static fn ($q) => $q->byDateAtBefore($date_at));
    }

    /**
     * @param ?int $state_id
     *
     * @return self
     */
    public function whenStateId(?int $state_id): self
    {
        return $this->when($state_id, static fn ($q) => $q->byStateId($state_id));
    }

    /**
     * @param ?int $user_id
     * @param ?int $vehicle_id
     * @param ?string $before_date_at
     * @param ?string $after_date_at
     *
     * @return self
     */
    public function whenUserIdVehicleIdDateAtBetween(?int $user_id, ?int $vehicle_id, ?string $before_date_at, ?string $after_date_at): self
    {
        return $this->whenUserId($user_id)
            ->whenVehicleId($vehicle_id)
            ->whenDateAtBetween($before_date_at, $after_date_at);
    }

    /**
     * @return self
     */
    public function withWhereHasPosition(): self
    {
        return $this->withWhereHas('position', static fn ($q) => $q->withCity());
    }

    /**
     * @return self
     */
    public function withVehicle(): self
    {
        return $this->with('vehicle');
    }
}
