<?php 

namespace event;

use event\EventPicture;
use reference\PictureType;

use ImageHelper;
use Datatables;
use Session;
use Config;
use \DB;
use Image;
use Illuminate\Support\Facades\Storage;
use Auth;

class EloquentEventPictureRepository implements EventPictureRepository
{
    public function find($id)
    {
        return EventPicture::find($id);
    }

    public function all()
    {
        return EventPicture::all();
    }

    public function create($input)
    {
        $eventPicture = new EventPicture;

        $eventPicture->event_id = @$input['event_id'];
        $eventPicture->picture_type_id = @$input['picture_type'];
        $eventPicture->url = $input['url'];

        $eventPicture->save();
    }

    public function delete($id)
    {
        $eventPicture = EventPicture::find($id);
        $eventPicture->delete();
    }
}