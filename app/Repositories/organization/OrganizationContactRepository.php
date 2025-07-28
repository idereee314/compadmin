<?php

namespace organization;

interface OrganizationContactRepository
{
    public function find($code);

    public function all();

    public function create($input);

    public function delete($code);

    public function update($code, $data);
}