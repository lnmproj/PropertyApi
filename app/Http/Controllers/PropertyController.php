<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyImages;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyController extends Controller
{   public $main;
    //this api for website
    public function getProperty(Request $request){
        $return = ['status' => true, 'message' => ''];
        $all_images = PropertyImages::where('type','image');
        $all_videos = PropertyImages::where('type','video');



        $all_images = $all_images->get();
        $all_videos = $all_videos->get();
        $all_property=Property::get();

        $data = [
          'images'=>[],
          'videos'=>[],
          'productdata'=>[]
        ];

        if (count($all_images)) {

            foreach ($all_images as $value) {
                array_push($data['images'], [
                    'id' => $value->id,
                     'type'=>$value->type,
                    'property_id'=>$value->property_id,
                    'images_video'=>$value->images_video
                ]);
            }

        }

        if (count($all_videos)) {

            foreach ($all_videos as $value) {
                array_push($data['videos'], [
                    'id' => $value->id,
                    'type'=>$value->type,
                    'property_id'=>$value->property_id,
                    'images_video'=>$value->images_video
                ]);
            }

        }
        if(count($all_property)){
        foreach ($all_property as $property) {

            array_push($data['productdata'], [
                 'id' => $property->id,
                  'seller_name' =>$property->seller_name,
                  'price_asked' => $property->price_asked,
                  'landarea' => $property->land_area,
                  'buildingarea' => $property->building_area,
                  'property_name' => $property->property_name,
                  'property_price' => $property->property_price,
                  'property_description' => $property->property_description,
                  'property_headline' => $property->property_headline,
                  'property_classification_id' =>$property->PropertyClass->property_classification,
                  'product_category_id' =>$property->ProductCategory->product_category_name,
                  'no_bedrooms' =>$property->no_bedrooms,
                  'no_toilets' =>$property->no_toilets,
                  'province' =>$property->Province->province_name,
                  'town' => $property->Town->town_name,
                  'slug'=>$property->slug,
                  'status'=>'Active'


            ]);
        }

    }

        $return['status'] = true;
        $return['data'] = $data;
        return response()->json($return, 200);

      }


public function  saveproperty(Request $request){


    $saveQuery = DB::table('property')->insertGetId(
        [


            'seller_name' => $request->seller_name,
            'price_asked' => $request->price_asked,
            'land_area' => $request->land_area,

            'building_area' => $request->building_area,
            'property_name' => $request->property_name,
            'property_headline' => $request->property_headline,
            'property_description' => $request->property_description,
            'property_classification_id' => $request->property_classification_id,
            'property_type_id' => $request->property_type_id,
            'product_category_id' => $request->product_category_id,
            'unit_no' => $request->unit_no,
            'house_lot_no' => $request->house_lot_no,
            'street_name' => $request->street_name,
            'property_building_name' => $request->property_building_name,
            'barangay_id' => $request->barangay_id,
            'town_id' => $request->town_id,
            'province_id' => $request->province_id,
            'subdivision_id' => $request->subdivison_id,
            'zipcode' => $request->zipcode,

            'no_bedrooms' => $request->no_bedrooms,
            'no_toilets' => $request->no_toilets,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'slug' => Str::slug($request->property_headline),
            'garage' => $request->garage,
            'cooling' => $request->cooling,
            'heatingtype' => $request->heatingtype,
            'elevator' => $request->elevator,
            'freewifi' => $request->freewifi,
            'exteriour' => $request->exteriour,
            'kitchen' => $request->kitchen,
            'year' => $request->year,
            'isFeatured'=>$request->isFeatured,
             'agent_id'=>$request->agent_id




        ]
    );
    if ($saveQuery > 0) {
        $mainid = $saveQuery;
//for type= image

        return response()->json(['property_id'=>$mainid,'message' => ' property  added successfully'], 200);
    }

}
public function  propertyimagesupload(Request  $request){
        $property1=DB::table('property')
            ->orderBy('id', 'DESC')->first();
//        dd($id);
    if($request->hasfile('images_video')) {

        $imageName = rand(1111, 9999) . time() . '.' . $request->images_video->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/featuredproperty/images');
        $request->images_video->move($destinationPath, $imageName);

        $saveQuery1 = DB::table('property_images')->insertGetId(
            [

                'property_id' => $property1->id,
                'images_video' => $imageName,
                'type' => 'Image',
                'isDefault' => $request->isDefault,
            ]);


    }
    //for type = video
    else{
        $saveQuery1 = DB::table('property_images')->insertGetId(
            [

                'property_id' => $property1->id,
                'images_video' => $request->video_link,
                'type' => 'Video',
                'isDefault' => 0
            ]);

    }
    if ($saveQuery1 > 0) {
        $id = $saveQuery1;
//for type= image

        return response()->json(['message' => 'image/video added successfully'], 200);
    }
}
//show properties on admin panel

public function  allproperty(){
    $getQuery = DB::table("property")->select(['*'])
        ->join('property_images','property_images.property_id','=','property.id')
        ->join('users','users.user_id','=','property.agent_id')
//        ->join('province','province.province_id','=','broker_association.province_id')
//        ->join('specialization','specialization.specialization_id','=','broker.specialization_id')
//
//        ->join('capability','capability.capability_id','=','capability.capability_id')
//        ->join('town','town.town_id','=','broker.town_id')
//        ->join('barangay','barangay.barangay_id','=','broker.barangay_id')
//        ->where('users.role_id',26)
       ->where('property_images.isDefault',true)
        ->where('property_images.type','Image')


     ->get();
    return response()->json(['resultData' => $getQuery], 200);
}

public function  deleteproperty(Request  $request){
    $deleteQuery = DB::table('property')->where('property.id', $request->property_id)->delete();
    if ($deleteQuery > 0) {

        return response()->json(['message' => 'Property deleted successfully'], 200);
    }
}

    public function  deletepropertyimages(Request  $request){
        $deleteQuery = DB::table('property_images')
            ->where('property_images.property_id', $request->property_id)
            ->where('property_images.id',$request->image_id)
            ->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Property image deleted successfully'], 200);
        }
    }




    public function updateproperty(Request $request)
    {


        $find = PropertyImages::find($request->property_id);

        $updateQuery = DB::table('property')
            ->where('property.id', $request->property_id)
            ->update([

                'seller_name' => $request->seller_name,
                'price_asked' => $request->price_asked,
                'land_area' => $request->land_area,
                'building_area' => $request->building_area,
                'property_name' => $request->property_name,
                'property_headline' => $request->property_headline,
                'property_description' => $request->property_description,
                'property_classification_id' => $request->property_classification_id,
                'property_type_id' => $request->property_type_id,
                'product_category_id' => $request->product_category_id,
                'unit_no' => $request->unit_no,
                'house_lot_no' => $request->house_lot_no,
                'street_name' => $request->street_name,
                'property_building_name' => $request->property_building_name,
                'barangay_id' => $request->barangay_id,
                'town_id' => $request->town_id,
                'province_id' => $request->province_id,
                'subdivision_id' => $request->subdivison_id,
                'zipcode' => $request->zipcode,

                'no_bedrooms' => $request->no_bedrooms,
                'no_toilets' => $request->no_toilets,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'slug' => Str::slug($request->property_headline),
                'garage' => $request->garage,
                'cooling' => $request->cooling,
                'heatingtype' => $request->heatingtype,
                'elevator' => $request->elevator,
                'freewifi' => $request->freewifi,
                'exteriour' => $request->exteriour,
                'kitchen' => $request->kitchen,
                'year' => $request->year,
                'isFeatured' => $request->isFeatured,
                'agent_id' => $request->agent_id


            ]);

    if($updateQuery) {
//        if($request->hasfile('images_video')) {
//
//            $imageName = rand(1111, 9999) . time() . '.' . $request->images_video->getClientOriginalExtension();
//            $destinationPath = public_path('/uploads/featuredproperty/images');
//            $request->images_video->move($destinationPath, $imageName);
//
//            $saveQuery1 = DB::table('property_images')->insertGetId(
//                [
//
//                    'property_id' => $request->property_id,
//                    'images_video' => $imageName,
//                    'type' => 'Image',
//                    'isDefault' => $request->isDefault,
//                ]);
//
//
//        }
//        //for type = video
//        else{
//            $saveQuery1 = DB::table('property_images')->insertGetId(
//                [
//
//                    'property_id' => $request->property_id,
//                    'images_video' => $request->images_video,
//                    'type' => 'Video',
//                    'isDefault' => 0
//                ]);
//
//        }
        return response()->json(['message' => 'property updated'], 200);
    }

    }
    public  function  showallimagesvideo(Request  $request){
        $db=DB::table('property_images')->where('property_id',$request->property_id)->get();
        return response()->json(['resultdata'=>$db], 200);
    }




}
