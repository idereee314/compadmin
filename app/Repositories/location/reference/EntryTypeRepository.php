<?php

namespace location\reference;

interface EntryTypeRepository
{
    public function find($id);

    public function all();
}