<?php

namespace user;

interface CompadUserRepository
{
  public function all();

  public function allPaginate();

	public function find($id);

	public function create($input);

 	public function findByEmail($email);

 	public function update($id, $input);

	public function findByUsernamePassword($username, $password);

	public function delete($id);
  
  public function getDatatableList($searchData);
}
