<?php
namespace App\Traits;
use Response;
use Config;
use App\Providers\ResponseMacroServiceProvider;
use Illuminate\Http\Request;
/**
 * RestApi
 *
 * @package                Laravel Rent Book App
 * @subpackage             RestApi
 * @category               Trait
 * @DateOfCreation         23 April 2018
 * @ShortDescription       This trait is responsible to Access the config of rest
 *                         and also generate the response for each request
 **/
trait RestApi
{

    protected function rest_config(){
        return Config::get('rest.rest_config');
    }

    protected function http_status_codes(){
        return Config::get('rest.http_status_codes');
    }

    protected function getRequestData($request){
        return $request->all();
    }

    protected function pagination($display_array, $page, $show_per_page){

        $page = $page < 1 ? 1 : $page;

        // start position in the $display_array
        // +1 is to account for total values.
        $start = ($page - 1) * ($show_per_page + 1);
        $offset = $show_per_page + 1;

        return array_slice($display_array, $start, $offset);
    }

     protected function resultResponse($code, $data=[], $errors = [], $msg)
     {
        $debug= app('request')->header('unencrypted');
        $rest_status_field    =  Config::get('rest.rest_config.rest_http_status_field_name');
        $rest_data_field      =  Config::get('rest.rest_config.rest_data_field_name');
        $rest_message_field   =  Config::get('rest.rest_config.rest_message_field_name');
        $rest_error_field     =  Config::get('rest.rest_config.rest_error_field_name');
        $rest_config          =  $this->rest_config();

        if($rest_config['rest_default_format'] == 'json'){
            $json=[
                $rest_status_field => $code,
                $rest_data_field => $data,
                $rest_message_field => $msg,
                $rest_error_field => $errors
            ];
            $response = response()->json($json, $code);
        }
        if($rest_config['rest_default_format'] == 'xml'){
            $response = response()->xml([
                $rest_status_field => $code,
                $rest_data_field => $data,
                $rest_message_field => $msg,
                $rest_error_field => $errors
            ]);
        }
        return $response;
     }

}
