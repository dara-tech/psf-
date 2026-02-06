<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Models\ProviderData;
use Ramsey\Uuid\Uuid;
use Cookie;
use App\Models\Token;
use App\Models\Site;


class ProviderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($token=null,$locale=null)
    {

          if ($locale == null)
          {
              $locale = App::setLocale("kh");
              
              
          }else{
             App::setLocale($locale);
          }

        if ($token == "index")
          {
            
            return view('index')->with ("parent", "provider")
                                ->with ("token","index")
                                ->with ("tokens",Token::get(['code','username']))
                                ->with("locale", $locale)
                                ->with('allsites', Site::distinct()->orderBy('province')->get(['username', 'site as site_kh','sitename as site_en','province_kh','province']));
                                
          }else if ($this->verifyToken($token))
          {
            
         
            $site = ($locale=="en")? $this->getTokenInfo($token)->site_en:$this->getTokenInfo($token)->site_kh;

            $uuid = Uuid::uuid4();

            return view('provider')->with("token",$token)
                               ->with ("locale", $locale)
                               ->with("parent","provider")
                               ->with ("uuid",$uuid )
                               ->with ('site', $site);
        }
        
    }

    public function savePage(Request $request, $token=null, $index=null)
    {
      
    
      if ($this->verifyToken($token))
      { 
      
        
     
         if ($request->locale == null)
         {
          App::setLocale("kh");
           
         }else{
           App::setLocale($request->locale);
         }
     
         $locale = $request->locale;
        
          switch ($index)
          {
          
            case 'consent':
                 return $this->saveConsent($token,$request);
            break;
            case 'section1':
              return $this->saveSection1($token,$request);
         break;
          }
        }
        
        return abort(404,'Page not found');
      }
    
    public function showPage($token=null, $locale=null,$uuid=null,$index=null)
    {
    
      if ($this->verifyToken($token))
      { 
          App::setLocale($locale );
          $site = ($locale=="en")? $this->getTokenInfo($token)->site_en:$this->getTokenInfo($token)->site_kh;
          switch ($index)
          {
            
            case 'consent':
                  break;
            case 'section1':
              return view('questionnaire/provider/section1')
                 
                  ->with("token",$token)
                  ->with ("locale", $locale)
                  ->with("parent",'provider')
                  ->with ("uuid",$uuid )
                  ->with("site",$site)
                  ;
                 break;
            case 'thank':
                  return view('thank')->with('token',$token)
                  ->with("parent","provider")
                  ->with ('local',$locale);
              break;
          }
        }
        
        return abort(404,'Page not found');
      }

      private function returnHome($token,$locale)
      {
        $site = ($locale=="en")? $this->getTokenInfo($token)->site_en:$this->getTokenInfo($token)->site_kh;
        $uuid = Uuid::uuid4();
        return view('provider')->with("token",$token)
                         ->with ("locale", $locale)
                         ->with("parent","provider")
                         ->with ("uuid",$uuid )
                         ->with ("site",$site)
                         ; 
      }

      private function saveConsent($token, $request)
      {
    
       
         $providerData = ProviderData::find($request->_uri);
              
         if ($providerData == null)
         {
        
            $providerData =   new ProviderData();
    
         }

        
        
         $providerData->ACKNOWLEDGE = $request->consent;
         
       
         $providerData->START = date('Y-m-d H:i:s');
         
         $providerData->USERNAME = $this->getTokenInfo($token)->username;
        
         $providerData->_URI =  $request->_uri;
         $providerData->DEVICEID = substr($request->header('User-Agent'),0,255);
         $providerData->_IS_COMPLETE = ($providerData->ACKNOWLEDGE ==1)?  0 :1;
    
         $providerData->save();

         if ($request->locale != null)
        {
          $locale = $request->locale;
        }else{
          $locale = "kh";
        }
    
         if($providerData->ACKNOWLEDGE ==1)
         {
         
            return redirect('/provider/'.$token .'/'.$locale.'/'.$providerData->_URI .'/section1');
         }else{
    
            return redirect('/provider/'.$token .'/'.$locale.'/'.$providerData->_URI .'/thank');
        
         }
    
       
      }

  private function saveSection1($token, $request)
  {

    $providerData =  ProviderData::findOrFail($request->_uri);

   
    if (!empty($providerData))
    {

        $providerData->dept = $request->dept ;     
        $providerData->ACKNOWLEDGE = $request->consent ?? $providerData->ACKNOWLEDGE;  
        $providerData->e1 = $request->e1 ;  
        $providerData->e2 = $request->e2 ;  
        $providerData->e3 = $request->e3 ;  
        $providerData->e4 = $request->e4 ;  
        $providerData->e5 = $request->e5 ;  
        $providerData->e6 = $request->e6 ;
        $providerData->_SUBMISSION_DATE = date('Y-m-d H:i:s');

        $providerData->_IS_COMPLETE = "1";
       
       $providerData->save();
      
          if ($request->locale != null)
          {
            $locale = $request->locale;
          }else{
            $locale = "kh";
          }

          return view('thank')->with("token",$token)
          ->with ("locale", $locale)
          ->with("parent","provider");
        //  ->with ("uuid",$uuid )
         // ->with ("site",$site)
          ; 
         // return redirect('/provider/'.$token .'/'.$locale.'/'.$request->_uri .'/thank');
      }

      return abort(404,'Page not found');
    
  }
    
    public function locale($locale) {
		\Session::put('locale', $locale);
		return redirect()->back();
    }

    public function getIp(){
      foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
          if (array_key_exists($key, $_SERVER) === true){
              foreach (explode(',', $_SERVER[$key]) as $ip){
                  $ip = trim($ip); // just to be safe
                  if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                      return $ip;
                  }
              }
          }
      }
  }
}