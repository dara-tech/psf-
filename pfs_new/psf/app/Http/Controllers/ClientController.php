<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Models\UserData;
use Ramsey\Uuid\Uuid;
use Cookie;
use App\Models\Site;
use App\Models\Token;


class ClientController extends Controller
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
      
          if ($locale == null || $locale == '')
          {
            $locale = App::setLocale("kh");
            
          }else{
            App::setLocale($locale);
          }

          if ($token == "index")
          {

            return view('index')->with ("parent", "client")
                                ->with ("token","index")
                                ->with ("tokens",Token::get(['code','username']))
                                ->with("locale", $locale)
                                ->with('allsites', Site::distinct()->orderBy('province')->get(['username', 'site as site_kh','sitename as site_en','province_kh','province']));
                                
          }else  if ($this->verifyToken($token))
          {
            
        
            $uuid = Uuid::uuid4();

            $site = ($locale=="en")? $this->getTokenInfo($token)->site_en:$this->getTokenInfo($token)->site_kh;
             return view('client')->with("token",$token)
                                ->with ("locale", $locale)
                                ->with("parent","client")
                                ->with ("uuid",$uuid )
                                ->with ("site",$site); 
                                
          }

        

          return abort(404,'Page not found');
          
    }

    public function save($token)
    {

      return $token;
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
            case 'section1a':
                  return $this->saveSection1a($token, $request);
            break;
            case 'section1a1':
                  return $this->saveSection1a1($token,$request);
            break;
            case 'section1b':
                  return $this->saveSection1b($token, $request);
            break;
            case 'section1c':               
                  return $this->saveSection1c($token, $request);
            break;
            case 'section5c':
                  return $this->saveSection5c($token, $request);
            break;
            case 'section6c':
                 return $this->saveSection6c ($token, $request);
            break;
            default:
              return abort(404,'Page not found');
          }
       }
  }

  private function saveConsent($token, $request)
  {
    
      $userData =  UserData::find($request->_uri);
    
      if (empty($userData))
      {

          $userData = new UserData();

      }

      $userData->ACKNOWLEDGE = $request->consent;
      $userData->START = date('Y-m-d H:i:s');
      $userData->USERNAME = $this->getTokenInfo($token)->username;
      $userData->_URI =  $request->_uri;
      $userData->DEVICEID = substr($request->header('User-Agent'),0,255);

      $userData->save();
        if ($request->locale == null)
        {
          $locale = "kh";
        }else{
          $locale = $request->locale;
        }
        
        

        if($userData->ACKNOWLEDGE ==1)
        {
            return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/section1');

        }else{

          return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/thank');
        
        }

   
  }

  private function saveSection1a($token, $request)
  {

    $userData =  UserData::findOrFail($request->_uri);

   
    if (!empty($userData))
    {

        $userData->Q1A = $request->q1a ;        
        $userData->Q2A = $request->q2a ;  
        $userData->Q3A = $request->q3a ;  
        $userData->Q4A = $request->q4a ;  
        $userData->Q5A = $request->q5a ;  
        $userData->_IS_COMPLETE = "0";
       
       $userData->save();

       if ($request->locale == null)
       {
         $locale = "kh";
       }else{
         $locale = $request->locale;
       }
       return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/section1a1');
      
    }

      return abort(404,'Page not found');
    
  }

  private function saveSection1a1($token, $request)
  {

    $userData =  UserData::findOrFail($request->_uri);

   
    if (!empty($userData))
    {

        $userData->Q6A = $request->q6a ;        
        $userData->Q7A = $request->q7a ;  
        $userData->Q8A = $request->q8a ;  
        $userData->Q9A = $request->q9a ;  
        $userData->Q10A = $request->q10a ;  
       
       $userData->save();
       
       if ($request->locale == null)
       {
         $locale = "kh";
       }else{
         $locale = $request->locale;
       }
       return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/section1b');

    }

    return abort(404,'Page not found');

  }


  private function saveSection1b($token, $request)
  {

    $userData =  UserData::findOrFail($request->_uri);

   
    if (!empty($userData))
    {

        $userData->Q1B = $request->q1b ;        
        $userData->Q2B = $request->q2b ;  
        $userData->Q3B = $request->q3b ;  
        $userData->Q4B = $request->q4b   ;  
        $userData->Q5B = $request->q5b  ;

       $userData->save();
       if ($request->locale == null)
       {
         $locale = "kh";
       }else{
         $locale = $request->locale;
       }
       return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/section1c');

    }

    return abort(404,'Page not found');
  }

  private function saveSection1c($token, $request)
  {

    $userData =  UserData::findOrFail($request->_uri);
   
    if (!empty($userData))
    {

        $userData->Q1C = $request->q1c ;        
        $userData->Q2C = $request->q2c ;  
        $userData->Q3C_1 = $request->q3c_1 ; 
        $userData->Q3C_2 = $request->q3c_2 ; 
        $userData->Q3C_3 = $request->q3c_3 ; 
        $userData->Q3C_4 = $request->q3c_4 ; 
        $userData->Q3C_5 = $request->q3c_5 ; 
        $userData->Q3C_6 = $request->q3c_6 ; 
        $userData->Q3C_7 = $request->q3c_7 ; 
        $userData->Q3C_8 = $request->q3c_8 ; 
        $userData->Q4C = $request->q4c   ;  
        

       $userData->save();

       if ($request->locale == null)
       {
         $locale = "kh";
       }else{
         $locale = $request->locale;
       }
       return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/section5c');
    }
    return abort(404,'Page not found');
  }

  private function saveSection5c($token, $request)
  {

    $userData =  UserData::findOrFail($request->_uri);
   
    if (!empty($userData))
    {

        $userData->Q5C1 = $request->q5c1 ;        
        $userData->Q5C2 = $request->q5c2 ;  
        $userData->Q5C3 = $request->q5c3 ; 
       
        $userData->save();

        if ($request->locale == null)
        {
          $locale = "kh";
        }else{
          $locale = $request->locale;
        }
        return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/section6c');
    }

    return abort(404,'Page not found');
  }

  private function saveSection6c ($token, $request)
  {    $userData =  UserData::findOrFail($request->_uri);
   
    
    if (!empty($userData))
    {
    
      
        $userData->Q6C_1 = $request->q6c_1 ; 
        $userData->Q6C_2 = $request->q6c_2 ; 
        $userData->Q6C_3 = $request->q6c_3 ; 
        $userData->Q6C_4 = $request->q6c_4 ; 
        $userData->Q6C_5 = $request->q6c_5 ; 
        $userData->Q6C_6 = $request->q6c_6 ; 
        $userData->Q6C_7 = $request->q6c_7 ; 
        $userData->Q6C_8 = $request->q6c_8 ; 
        $userData->Q7C = $request->q7c ;        
        $userData->Q8C = $request->q8c ;  
        $userData->Q9C_1 = $request->q9c_1   ;  
        $userData->Q9C_2 = $request->q9c_2   ;  
        $userData->Q9C_3 = $request->q9c_3   ;  
        $userData->Q9C_4 = $request->q9c_4   ;  
        $userData->Q9C_5 = $request->q9c_5   ;  
        $userData->Q10C = $request->q10c   ;  
        $userData->Q11C = $request->q11c   ;  
        $userData->Q12C = $request->q12c   ;  
        $userData->Q13C = $request->q13c   ;  
        $userData->Q14C = $request->q14c   ;  
        
        $userData->_IS_COMPLETE = "1";

       $userData->save();

       if ($request->locale == null)
       {
         $locale = "kh";
       }else{
         $locale = $request->locale;
       }

       return redirect('/client/'.$token .'/'.$locale.'/'.$request->_uri.'/thank');
 
    }

    return abort(404,'Page not found');

  }

    public function locale($locale) {
		\Session::put('locale', $locale);
		return redirect()->back();
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
            case 'thank':
              return view('thank')->with('token',$token)
              ->with("parent","client")
              ->with ('local',$locale);
               break;
            default:
              return view('questionnaire/client/'.$index)
                   ->with ("locale", $locale)
                  ->with("token",$token)
                  ->with("parent","client")
                  ->with ("uuid",$uuid )
                  ->with ("site",$site);  
              break;
          }
      }
      
      return abort(404,'Page not found');
    }

    
}
