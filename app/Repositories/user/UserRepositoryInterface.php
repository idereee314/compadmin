<?php

namespace user;

interface UserRepositoryInterface
{
  public function all();

  public function allPaginate();

  public function find($id);

  public function create($input);

  public function findByEmail($email);

  public function getOnlineUsers();

  public function getOfflineUsers($onlineUsers);

  public function update($id, $input);

  public function updateLastActive($user);

  public function updateUserPassword($id, $input);

  public function delete($id);
}
