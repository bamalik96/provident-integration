<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Amenity;
use App\Models\City;
use App\Models\Community;
use App\Models\PropertyAmenity;
use App\Models\PropertyType;
use Illuminate\Support\Str;

class WebServicePropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //  $response = simplexml_load_file(storage_path('/app/response.xml'));
        //  $response =  json_encode($response, JSON_PRETTY_PRINT);
        //   $data = json_decode($response, TRUE); // convert the JSON-encoded string to a PHP variable

        // // $data =  json_decode(file_get_contents(storage_path() . "/app/response.json"), true);
        // $response =  json_encode($response, JSON_PRETTY_PRINT);



        // $response = Http::get('https://manda.propertybase.com/api/v2/feed/00D3h000006dlegEAA/generic/a0L3h000001lBtmEAE/full');
        $response = simplexml_load_file('https://manda.propertybase.com/api/v2/feed/00D3h000006dlegEAA/generic/a0L3h000001lBtmEAE/full');
        $response =  json_encode($response, JSON_PRETTY_PRINT);
        $data = json_decode($response, TRUE); // convert the JSON-encoded string to a PHP variable

        $data = ($data['listing']);

        foreach ($data as $key => $value) {

            $general_listing_information = $value['general_listing_information'];
            $address_information = $value['address_information'];

            /***
             *Community Add
             */
            $community = Community::updateOrCreate([
                'name' => getValueRemoveExtraSpace($value, 'custom_fields.pba_uaefields__community_propertyfinder')
            ], [
                'name' => getValueRemoveExtraSpace($value, 'custom_fields.pba_uaefields__community_propertyfinder')
            ]);

            /***
             * City Add
             */
            $city = City::updateOrCreate([
                'name' => getValueRemoveExtraSpace($address_information, 'city')
            ], [
                'name' => getValueRemoveExtraSpace($address_information, 'city')
            ]);

            /**
             * Agent Create
             */
            $agent = Agent::updateOrCreate([
                'email' => getValueRemoveExtraSpace($value, 'listing_agent.listing_agent_email')
            ], [
                'email' => getValueRemoveExtraSpace($value, 'listing_agent.listing_agent_email'),
                'first_name' => getValueRemoveExtraSpace($value, 'listing_agent.listing_agent_firstname'),
                'last_name' => getValueRemoveExtraSpace($value, 'listing_agent.listing_agent_lastname'),
                'phone_no' => getValueRemoveExtraSpace($value, 'listing_agent.listing_agent_phone')
            ]);

            /***
             * Property TYpe Add
             */
            if (getValueRemoveExtraSpace($general_listing_information, 'propertytype')) {
                $property_sub_type = getValueRemoveExtraSpace($value, 'custom_fields.pba_uaefields__property_sub_type');
                $property_type = PropertyType::updateOrCreate([
                    'title' => getValueRemoveExtraSpace($general_listing_information, 'propertytype')
                ], [
                    'title' => getValueRemoveExtraSpace($general_listing_information, 'propertytype'),
                    'is_commercial' => Str::contains($property_sub_type, 'Commercial') ? true : false,
                    'is_residential' =>  Str::contains($property_sub_type, 'Residential') ? true : false,
                ]);
            }


            /***
             * Property Add
             */
            $bedrooms = getValueRemoveExtraSpace($general_listing_information, 'bedrooms');
            $fullbathrooms = getValueRemoveExtraSpace($general_listing_information, 'fullbathrooms');

            $property =  Property::updateOrCreate([
                'property_code' => getValueRemoveExtraSpace($value, 'id')
            ], [
                'listing_title' => getValueRemoveExtraSpace($general_listing_information, 'listing_title'),
                'property_code' => getValueRemoveExtraSpace($value, 'id'),
                'listingprice' => getValueRemoveExtraSpace($general_listing_information, 'listingprice'),
                'currency_iso_code' => getValueRemoveExtraSpace($general_listing_information, 'currency_iso_code'),
                'listingtype' => getValueRemoveExtraSpace($general_listing_information, 'listingtype'),
                'lotsize' => getValueRemoveExtraSpace($general_listing_information, 'lotsize'),
                'property_number' => getValueRemoveExtraSpace($general_listing_information, 'property'),
                'totalarea' => getValueRemoveExtraSpace($general_listing_information, 'totalarea'),
                'description' => getValue($general_listing_information, 'description'),
                'fullbathrooms' => $fullbathrooms ? $fullbathrooms : 0,
                'bedrooms' => $bedrooms ? $bedrooms : 0,
                'address' => getValueRemoveExtraSpace($address_information, 'address'),
                'latitude' => getValueRemoveExtraSpace($address_information, 'latitude'),
                'longitude' => getValueRemoveExtraSpace($address_information, 'longitude'),
                'property_status_prov' => getValueRemoveExtraSpace($value, 'custom_fields.property_status_prov'),
                'mode' => getValueRemoveExtraSpace($value, '@attributes.mode'),
                'property_created' => getValueRemoveExtraSpace($value, '@attributes.created_at'),
                'property_updated' => getValueRemoveExtraSpace($value,  '@attributes.timestamp'),
                'city_id' => getValueRemoveExtraSpace($city, 'id'),
                'property_type_id' => getValueRemoveExtraSpace($property_type, 'id'),
                'agent_id' => $agent->id,
                'images' => getValue($value, 'listing_media.images.image'),
                'property_finder_region' => getValueRemoveExtraSpace($value, 'custom_fields.pba_uaefields__propertyfinder_region'),
                'community_id' => $community->id
            ]);

            $property_ids[] = $property->id;
            /***
             * Property Amenities
             */
            $property_amenities_list = getValueRemoveExtraSpace($value, 'custom_fields.pba_uaefields__private_amenities');
            if ($property_amenities_list) {
                $property_amenities_list = explode(';', trim($property_amenities_list));

                foreach ($property_amenities_list as $key => $item) {
                    $amenity = Amenity::updateOrCreate([
                        'name' => $item
                    ], [
                        'name' => $item
                    ]);
                    $property_amenity = PropertyAmenity::updateOrCreate([
                        'property_id' => $property->id,
                        'amenity_id' => $amenity->id
                    ], [
                        'property_id' => $property->id,
                        'amenity_id' => $amenity->id
                    ]);
                    $property_amenity_ids[] = $property_amenity->id;
                }
            }
        }
        PropertyAmenity::whereNotIn('id', $property_amenity_ids)->delete();
        Property::whereNotIn('id', $property_ids)->delete();
    }
}
