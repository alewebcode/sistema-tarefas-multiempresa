<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'user_id',
        'company_id'
    ];
    protected $keyType = 'string';

    protected $casts = [
        'due_date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }


    const STATUS_PENDENTE = 'pendente';
    const STATUS_EM_ANDAMENTO = 'em_andamento';
    const STATUS_CONCLUIDA = 'concluida';

    const PRIORITY_BAIXA = 'baixa';
    const PRIORITY_MEDIA = 'media';
    const PRIORITY_ALTA = 'alta';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDENTE,
            self::STATUS_EM_ANDAMENTO,
            self::STATUS_CONCLUIDA
        ];
    }

    public static function getPriorities()
    {
        return [
            self::PRIORITY_BAIXA,
            self::PRIORITY_MEDIA,
            self::PRIORITY_ALTA
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}