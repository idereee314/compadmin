<?php

namespace reference;

interface EventEntriesRepository
{
  public function all();

  public function allPaginate();

  public function find($id);

  public function create($input);

  public function update($id, $input);

  public function delete($id);
  
  public function getDatatableList($searchData);
}
