<?php

namespace user;

interface CompadRoleRepository
{
  public function all();

  public function allPaginate();

  public function find($id);
}
