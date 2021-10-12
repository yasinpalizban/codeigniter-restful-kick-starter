<?php

namespace Modules\Shared\Interfaces;


interface AggregationInterface
{
    public function aggregate(array $pipeLine): array;


    public function aggregatePagination(array $pipeLine): array;
}