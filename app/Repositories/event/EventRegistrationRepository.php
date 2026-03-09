<?php

namespace event;

interface EventRegistrationRepository
{
  public function all();

  public function allPaginate();

  public function find($id);

  public function create($input);

  public function update($id, $input);

  public function delete($id);
  
  public function getDatatableList($searchData);

  public function getMatchesForBracketDisplay($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId);
}
