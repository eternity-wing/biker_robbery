<?php

namespace App\Controller\APi\Bike;

use App\Entity\Bike;

/**
 * Class BikeStealingResolver.
 */
class BikeStealingResolver extends BikeOperation
{
    /**
     * @return Bike|mixed
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function __invoke(Bike $data)
    {
        $this->bikeFactory->resolve($data, null);

        return $data;
    }
}
