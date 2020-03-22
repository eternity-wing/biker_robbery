<?php

namespace App\Controller\APi\Bike;

use App\Entity\Bike;

/**
 * Class BikeDestroyer.
 */
class BikeDestroyer extends BikeOperation
{
    /**
     * @return mixed|void
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function __invoke(Bike $data)
    {
        $this->bikeFactory->delete($data, null);
    }
}
