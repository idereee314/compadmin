<?php

namespace location\reference;

interface ObjectTypeRepository
{
    public function find($id);

    public function all();
}