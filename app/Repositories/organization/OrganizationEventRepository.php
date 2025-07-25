<?php

namespace organization;

interface OrganizationEventRepository
{
    public function find($code);

    public function all();

    public function create($input);

    public function delete($code);

    public function update($code, $data);
}