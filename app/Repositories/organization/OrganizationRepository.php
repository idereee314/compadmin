<?php

namespace organization;

interface OrganizationRepository
{
  public function all();

  public function allPaginate();

  public function find($id);
}
