<?php

namespace reference;

interface EventEntriesFeeRepository
{
  public function all();

  public function allPaginate();

  public function find($id);

  public function create($input);

  public function update($id, $input);

  public function delete($id);

  public function getEntriesFeeByEntryId($entries);
  
  public function getDatatableList($searchData);
}
