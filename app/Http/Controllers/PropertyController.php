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
            'select_floor_level' => $request->select_floor_level,
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
             'agent_id'=>$request->agent_id,
            'rental_price_asked'=>$request->rental_price_asked,
            'minimum_rental_period_rent'=>$request->minimum_rental_period_rent,
            'car_spaces_rent'=>$request->car_spaces_rent,
            'date_of_month_rent_due'=>$request->date_of_month_rent_due,
            'period_can_extend'=>$request->period_can_extend,
            'date_rental_started'=>$request->date_rental_started,
            'current_rental_expires'=>$request->current_rental_expires,
            'rental_switch_on'=>$request->rental_switch_on,
            'sale_price'=>$request->sale_price,
            'price_per_sq_m'=>$request->price_per_sq_m,
            'sale_switch_on'=>$request->sale_switch_on,
            'agri_type'=>$request->agri_type,
            'car_spaces_uncovered_property'=>$request->car_spaces_uncovered_property,
            'garage_spaces_covered_property'=>$request->garage_spaces_covered_property,
            'minimum_rental_period_sale'=>$request->minimum_rental_period_sale,
            'fireplace'=>$request->fireplace,
            'swimming_pool'=>$request->swimming_pool,
        ]
    );
    if ($saveQuery > 0) {
        $mainid = $saveQuery;

        return response()->json(['property_id'=>$mainid,'message' => ' property  added successfully'], 200);
    }

}
public function  propertyimagesupload(Request  $request){

    if($request->hasfile('images_video')) {

        $imageName = rand(1111, 9999) . time() . '.' . $request->images_video->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/featuredproperty/images');
        $request->images_video->move($destinationPath, $imageName);

        $saveQuery1 = DB::table('property_images')->insertGetId(
            [

                'property_id' => $request->property_id,
                'images_video' => $imageName,
                'type' => 'Image',
                'isDefault' => $request->isDefault,
                'description' => $request->description,
            ]);


    }
    //for type = video
    else{
        $saveQuery1 = DB::table('property_images')->insertGetId(
            [

                'property_id' => $request->property_id,
                'images_video' => $request->video_link,
                'type' => 'Video',
                'isDefault' => 0,
                'description' => $request->description,
            ]);

    }
    if ($saveQuery1 > 0) {
        $id = $saveQuery1;
//for type= image

        return response()->json(['message' => 'image/video added successfully'], 200);
    }
}
//show properties on admin panel

public function  allproperty(Request  $request){
    $itemsPerPage = $request->itemsPerPage;
    $sortColumn = $request->sortColumn;
    $sortOrder = $request->sortOrder;
    $getQuery = DB::table("property")->select(['property.id','property.seller_name','property.land_area','property.building_area','property.land_area','property.property_name','property.property_headline'
        ,'property.property_description','property.price_asked','property.property_classification_id'
    ,'property.property_type_id','property.product_category_id','property.unit_no','property.house_lot_no','property.street_name','property.property_building_name','property.town_id','property.province_id','property.barangay_id',
        'property.subdivision_id','property.zipcode','property.select_floor_level','property.no_bedrooms','property.no_toilets','property.latitude'
    ,'property.longitude','property.latitude','property.slug','property.garage','property.cooling','property.heatingtype','property.elevator',
        'property.year','property.freewifi','property.exteriour','property.kitchen','property.isFeatured','property.agent_id','property.agri_type','property.rental_price_asked','property.minimum_rental_period_rent',
        'property.car_spaces_rent','property.date_of_month_rent_due','property.period_can_extend','property.car_spaces_rent','property.date_rental_started','property.current_rental_expires','property.rental_switch_on'
    ,'property.sale_price','property.sale_switch_on','property.price_per_sq_m','property.car_spaces_uncovered_property','property.garage_spaces_covered_property'
    ,'property.minimum_rental_period_sale','property.fireplace','property.swimming_pool','property_images.images_video'])
        ->join('property_images','property_images.property_id','=','property.id')
        ->join('users','users.user_id','=','property.agent_id')
        ->join('property_classification','property_classification.property_classification_id','=','property.property_classification_id')
        ->join('product_category','product_category.product_category_id','=','property.product_category_id')
        ->join('province','province.province_id','=','property.province_id')


        ->join('subdivisions','subdivisions.subdivision_id','=','property.subdivision_id')
        ->join('town','town.town_id','=','property.town_id')
        ->join('barangay','barangay.barangay_id','=','property.barangay_id')
       ->where('property_images.isDefault',true)
        ->where('property_images.type','Image')
        ->orderBy($sortColumn, $sortOrder)
        ->paginate($itemsPerPage);
//          ->count();

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


        $findimage = PropertyImages::where('id',$request->imageid)->where('property_id',$request->property_id)
        ->where('type','Image')->first();
//        dd($findimage);
        $findvideo = PropertyImages::where('id',$request->imageid)->where('property_id',$request->property_id)
            ->where('type','Video')->first();

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
                'agent_id' => $request->agent_id,
                'rental_price_asked'=>$request->rental_price_asked,
                'minimum_rental_period'=>$request->minimum_rental_period,
                'car_spaces'=>$request->car_spaces,
                'date_of_month_rent_due'=>$request->date_of_month_rent_due,
                'period_can_extend'=>$request->period_can_extend,
                'date_rental_started'=>$request->date_rental_started,
                'current_rental_expires'=>$request->current_rental_expires,
                'rental_switch_on'=>$request->rental_switch_on,
                'sale_price'=>$request->sale_price,
                'price_per_sq_m'=>$request->price_per_sq_m,
                'sale_switch_on'=>$request->sale_switch_on,
                'agri_type'=>$request->agri_type,


            ]);
//      if(empty($findimage)){
//          if($request->hasfile('images_video')) {
//
//              $imageName = rand(1111, 9999) . time() . '.' . $request->images_video->getClientOriginalExtension();
//              $destinationPath = public_path('/uploads/featuredproperty/images');
//              $request->images_video->move($destinationPath, $imageName);
//
//              $saveQuery1 = DB::table('property_images')->insertGetId(
//                  [
//
//                      'property_id' => $request->property_id,
//                      'images_video' => $imageName,
//                      'type' => 'Image',
//                      'isDefault' => $request->isDefault,
//                  ]);
//
//
//          }
//      }
//
//      if(empty($findvideo)){
//          $saveQuery1 = DB::table('property_images')->insertGetId(
//              [
//
//                  'property_id' => $request->property_id,
//                  'images_video' => $request->video_link,
//                  'type' => 'Video',
//                  'isDefault' => 0
//              ]);
//      }

//    if ($request->type == "Image") {
//        if (!empty($request->file('images_video'))) {
//            $imageName = rand(1111, 9999) . time() . '.' . $request->images_video->getClientOriginalExtension();
//            $destinationPath = public_path('/uploads/featuredproperty/images');
//            $request->images_video->move($destinationPath, $imageName);
//
//            $pro_photo = $imageName;
//
//        } else {
//            $pro_photo = $findimage->images_video;
//
//        }
//        $findimage->type = 'Image';
//
//        $findimage->images_video = $pro_photo;
//
//        $findimage->save();
//    }
//    if ($request->type == "Video") {
//        if (!empty($request->videolink)) {
//
//            $pro_video = $request->videolink;
//
//        } else {
//            $pro_video = $findvideo->images_video;
//        }
//        $findvideo->type = 'Video';
//
//        $findvideo->images_video = $pro_video;
//        $findvideo->save();
//    }

        return response()->json(['message' => 'property updated'], 200);


    }
    public  function  showallimagesvideo(Request  $request){
        $dbimage=DB::table('property_images')->where('property_id',$request->property_id)

            ->get();

        return response()->json(['resultdata' => $dbimage], 200);
    }

    public function  propertyimagesisDefault(Request $request){
        $dbimage=DB::table('property_images')->where('property_id',$request->property_id)
         ->where('id',$request->image_id);

        if($dbimage){
            $dbimage->update([
               'property_images.isDefault'=>$request->isDefault
            ]);
        }
        $isDefault=DB::table('property_images')->where('property_id',$request->property_id)
            ->where('id',$request->image_id)->first();
        return response()->json(['isDefault' => $isDefault->isDefault], 200);

    }

    public function  propertyamenitiesupdate(Request  $request){
        $checkeddata=[];
    $masteramenities=DB::table('master_amenities')->where('status','active')->get();
    $mappedamenities=DB::table('master_amenities')
        ->join('property_amenities','property_amenities.property_id','=','master_amenities.id')
        ->where('property_amenities.property_id',$request->property_id)
        ->where('master_amenities.status','active')
->get();
        foreach ($mappedamenities as $value) {
            array_push($checkeddata, [
                'property_id'=>$value->property_id,
                'amenities_id'=>$value->amenities_id,
                'status'=>$value->status,
                'checked'=>'checked'
            ]);
        }
    return response()->json([$masteramenities,$checkeddata]);
    }




}
