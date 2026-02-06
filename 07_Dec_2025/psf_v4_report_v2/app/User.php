<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserSite;
use App\Models\Site;
use Hash;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = ['name', 'email', 'password', 'remember_token'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
       
    }
    
    public function sites(): MorphToMany
    {   return $this->morphToMany(
            Site::class,
            'model',
            'user_belong2_sites',
            'model_id',
            'site_id'
        );
      
    }

   
    public function belong2Site(...$sites)
    {
    
        $siteList = collect($sites)
                    ->flatten()
                    ->map(function ($site)
                    {
                       if(empty($site))
                       {
                           return false;
                       }
                       return Site::where("site",$site)->first()->id;
                    })                   
                    ->all();
      
        return $this->sites()->sync( $siteList);
    }

}
