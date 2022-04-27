<?php

namespace event;

interface EventRegistrationStatusRepository
{
  public function all();

  public function find($id);
}
