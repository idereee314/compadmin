<?php

namespace location;

// Repositories
use location\unit\AimagCityRepository as AimagCity;
use location\unit\SoumDistrictRepository as SoumDistrict;
use location\unit\BagKhorooRepository as BagKhoroo;

// Models
use location\unit\AmaigCity as AimagCityModel;
use location\unit\SoumDistrict as SoumDistrictModel;
use location\unit\BagKhoroo as BagKhorooModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class UnitController extends Controller
{
    public function __construct(AimagCity $aimagCity, SoumDistrict $soumDistrict, BagKhoroo $bagKhoroo) {

        $this->view_path = "location.unit";
        $this->aimagCity = $aimagCity;
        $this->soumDistrict = $soumDistrict;
        $this->bagKhoroo = $bagKhoroo;
    }

    public function getSoumDistrictByAimagCityId()
    {
        $input = Input::all();
        
        $soumDistrict = $this->soumDistrict->getSoumDistrictByAimagCity(@$input['aimagCityId']);
        return json_encode($soumDistrict);
    }

    public function getBagKhorooBySoumDistrictId()
    {
        $input = Input::all();

        $bagKhoroo = $this->bagKhoroo->getBagKhorooBySoumDistrict(@$input['soumDistrictId']);
        return json_encode($bagKhoroo);
    }
}
