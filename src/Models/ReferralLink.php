<?php

namespace Corals\Modules\Referral\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ReferralLink extends BaseModel implements HasMedia
{
    use PresentableTrait;
    use LogsActivity;
    use InteractsWithMedia ;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'referral_programs.models.referral_link';

    protected $guarded = ['id'];

    protected $fillable = ['user_id', 'referral_program_id', 'translation_language_code'];

    protected static function boot()
    {
        static::creating(function (ReferralLink $model) {
            $model->generateCode();
        });

        parent::boot();
    }

    private function generateCode()
    {
        $this->code = \Str::random(10);
    }

    public function getLinkAttribute()
    {
        return url($this->program->uri) . '?ref=' . $this->code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(ReferralProgram::class, 'referral_program_id');
    }

    public function relationships()
    {
        return $this->hasMany(ReferralRelationship::class);
    }
}
