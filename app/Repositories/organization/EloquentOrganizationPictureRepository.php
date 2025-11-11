<?php 

namespace organization;

use organization\OrganizationPicture;
use reference\PictureType;

use ImageHelper;
use Datatables;
use Session;
use Config;
use \DB;
use Image;
use Illuminate\Support\Facades\Storage;
use Auth;

class EloquentOrganizationPictureRepository implements OrganizationPictureRepository
{
    public function find($id)
    {
        return OrganizationPicture::find($id);
    }

    public function all()
    {
        return OrganizationPicture::all();
    }

    public function create($input)
    {
        $organizationPicture = new OrganizationPicture;
        $organizationPicture->organization_id = @$input['picture_org_id'];
        $organizationPicture->picture_type_id = @$input['picture_type'];
        $organizationPicture->url = $input['url'];

        $organizationPicture->save();
    }

    public function delete($id)
    {
        $organizationPicture = OrganizationPicture::find($id);
        
        $organizationPicture->delete();
    }
}