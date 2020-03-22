<?php

namespace App\Controller\APi\Bike;

use App\Entity\Bike;

/**
 * Class BikeResponsibleAssigner.
 */
class BikeResponsibleAssigner extends BikeOperation
{
    /**
     * @return Bike|mixed
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function __invoke(Bike $data)
    {
        $this->bikeFactory->store($data);
        $this->bikeFactory->assignResponsible($data, null);

        return $data;
    }
}
