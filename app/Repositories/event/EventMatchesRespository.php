<?php
namespace event;

interface EventMatchesRespository {
    public function find($id);
    public function create($input);
    public function update($id, $input);
    public function delete($id);
    public function generateMatches($event_id, $input);
    public function getMatchesByEventId($id);
}