<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Entities\BranchDepartment;
use Log;
use RoleHelper;

class Region extends Model
{
    use SoftDeletes;

//     protected $connection = 'oracle';

    protected $table = "regions";

    protected $primaryKey = 'region_id_pk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'number',
        'zone_id_fk',
        'manager_id_fk',
        'reject_reason',
        'rejected_by_fk',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    public function branches()
    {
        return $this->hasMany('App\Models\Entities\BranchDepartment', 'region_id_fk', 'region_id_pk');
    }

    public function zone()
    {
        return $this->belongsTo('App\Models\Entities\Zone', 'zone_id_fk', 'zone_id_pk');
    }

    public function manager()
    {
        return $this->belongsTo('App\Models\Entities\User', 'manager_id_fk', 'user_id_pk');
    }

    public function rejectedBy()
    {
        return $this->belongsTo('App\Models\Entities\User', 'rejected_by_fk', 'user_id_pk');
    }

    public function save(array $options = Array())
    {
        $dirty = $this->getDirty();
        $originalManagerId = $this->getOriginal('manager_id_fk');
        parent::save();
        /**
         */
        Log::info('Region dirty >>' . json_encode($dirty));
        if (isset($dirty['status']) || isset($dirty['deleted_at'])) {}

        /**
         * Assign back the user role to the removing manager
         */
        if ($originalManagerId !== null && $originalManagerId !== $this->manager_id_fk) {
            self::syncRole(RoleHelper::getUserRole(), $originalManagerId);
        }
        /**
         * Assign rm role.
         */
        if ($this->manager_id_fk != null)
            self::syncRole(RoleHelper::getRegionalManagerRole(), $this->manager_id_fk);

        if (isset($options['solIds'])) {
            $solIds = explode(',',$options['solIds']);
            /**
             * Removed non existing Ids after update.
             */
            BranchDepartment::where('region_id_fk' ,$this->region_id_pk)->whereNotIn('sol_id', $solIds)->update(['region_id_fk'=> null]);
            /**
             * Update branches with sol_id
             */
            foreach ($solIds as $solId){
                BranchDepartment::where('sol_id', $solId)->update(['region_id_fk' => $this->region_id_pk]);
            }
        }

        return parent::save();
    }

    /**
     * Call Before the event get fired
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($region) {
            self::syncRole(RoleHelper::getUserRole(), $region->manager_id_fk);
        });
        static::saving(function($zone){
        });
        static::updating(function($zone){
        });
    }

    private static function syncRole($role,$id){
        $user = User::find($id);
        if($user)
            $user->syncRoles([$role]);
    }
}
