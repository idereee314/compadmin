<?php
namespace event;

interface EventConfigDaysRepository {
    public function find($id);
    public function create($input);
    public function update($id, $input);
    public function delete($id);
    public function getMatByEventId($event_id);

    public function generateMatAndDays($event_id, $mat, $start_date, $end_date);
}