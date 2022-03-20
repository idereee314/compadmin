<?php

namespace user;

interface UserRepository
{
  public function all();

  public function allPaginate();

  public function find($id);

  public function searchUser($data);
}
