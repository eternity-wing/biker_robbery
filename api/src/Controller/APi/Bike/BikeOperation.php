<?php

namespace App\Controller\APi\Bike;

use App\Entity\Bike;
use App\Factory\BikeFactory;

/**
 * Class BikeOperation.
 */
abstract class BikeOperation
{
    /**
     * @var BikeFactory
     */
    protected $bikeFactory;

    /**
     * BikeOperation constructor.
     */
    public function __construct(BikeFactory $bikeFactory)
    {
        $this->bikeFactory = $bikeFactory;
    }

    /**
     * @return mixed
     */
    abstract public function __invoke(Bike $data);
}
