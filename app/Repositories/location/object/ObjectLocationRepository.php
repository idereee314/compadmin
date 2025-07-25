<?php

namespace location\object;

interface ObjectLocationRepository
{
    public function find($code);

    public function all();
}