<?php

namespace organization;

interface OrganizationPictureRepository
{
    public function find($code);

    public function all();

    public function create($input);

    public function delete($code);
}