<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyImages;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
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


}
