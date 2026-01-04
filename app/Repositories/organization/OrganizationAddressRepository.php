<?php

namespace organization;

interface OrganizationAddressRepository
{
    public function find($code);

    public function all();

    public function create($input);

    public function delete($code);

    public function update($id, $data);
}