<?php

namespace event;

interface EventTeamRegistrationStatusRepository
{
  public function all();

  public function find($id);
}
