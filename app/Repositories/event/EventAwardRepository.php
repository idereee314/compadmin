<?php

namespace event;

interface EventAwardRepository
{
  public function all();

  public function allPaginate();
}
