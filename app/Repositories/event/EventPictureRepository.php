<?php

namespace event;

interface EventPictureRepository
{
    public function find($code);

    public function all();
}