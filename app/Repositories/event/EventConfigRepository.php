<?php

namespace event;

interface EventConfigRepository
{
    public function all();

    public function allPaginate();
  
    public function find($id);
  
    public function create($input);
  
    public function update($id, $input);
  
    public function delete($id);

    public function copyEventConfig($eventConfigId, $input);
    
    public function getDatatableList($searchData);
}
