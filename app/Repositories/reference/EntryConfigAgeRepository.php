<?php

namespace reference;

interface EntryConfigAgeRepository
{
  public function all();

  public function allPaginate();

  public function find($id);

  public function create($input);

  public function update($id, $input);

  public function delete($id);

  // public function getConfigAgeByEntryId($entries);
  
  public function getDatatableList($searchData);
}
