<?php

namespace location\object;

interface EntranceRepository
{
    public function find($code);

    public function all();
}