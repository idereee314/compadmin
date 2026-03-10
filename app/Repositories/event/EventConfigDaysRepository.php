<?php

namespace event;

interface EventConfigDaysRepository
{
    public function find($id);

    public function create($input);

    public function update($id, $input);

    public function delete($id);

    public function getMatByEventId($event_id);

    public function generateMatAndDays($event_id, $mat, $start_date, $end_date);

    public function dictData($event_id);

    public function getLockedBracketIds($event_id);

    public function getCurrentBracketAssignments($event_id);

    public function removeBracketFromMat($event_id, $day_id, $mat_id, $bracket_id);
}