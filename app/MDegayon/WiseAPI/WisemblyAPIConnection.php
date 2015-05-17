<?php
namespace MDegayon\WiseAPI;

use Httpful\Request as Request;
use MDegayon\Wiz\WizUser as WizUser;


/**
 * Class responsable of connection to the Wisembly API
 *
 * @author Miguel Degayon
 */
class WisemblyAPIConnection
{
    
    const   API_ADDRESS = 'https://api.wisembly.com',
            SUCCESSFUL_REQUEST_CODE = 200,
            API_V4 =   'api/4';
    
    public static function connect($email, $secret, $app_id, $hash)
    {
    
        $connection = false;
            
        $body =     '{"email" : "'.$email.
                    '","secret":"'.$secret.
                    '","app_id" : "'.$app_id.
                    '", "hash" : "'.$hash.'"}';

        $response = Request::post( 
                WisemblyAPIConnection::API_ADDRESS .'/'.
                WisemblyAPIConnection::API_V4 . '/authentication')
                ->sendsJson()
                ->body($body)
                ->send();
        
        if ($response->code != WisemblyAPIConnection::SUCCESSFUL_REQUEST_CODE){

            throw new InvalidArgumentException
                    ("Error while trying to connect to API : ");
        }
        $user = WisemblyAPIConnection::createUserFromResponse($response);
        
        return  $response;
    }
    
    public static function connectAnonymous($appId, $appSecret)
    {
        
    }    
    
   private static function createUserFromResponse(Httpful\Response $response)
   {

        $user = false;
        
        try{
            return new WizUser( $response->body->success->data->user->hash, 
                                 $response->body->success->data->user->name, 
                                 $response->body->success->data->user->email, 
                                 $response->body->success->data->user->company);
           
        }catch (Exception $e){
           
            $user = false;
        }
       
        return $user;
   }
}

?>
