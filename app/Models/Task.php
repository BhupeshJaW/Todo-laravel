<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'description',
        'due_date',
        'done',
    ];

    protected $casts = [
        'due_date' => 'date',
        'done' => 'boolean',
    ];

    /**
     * Scope for ordering tasks by urgency: due dates first (oldest first), then no due dates (latest created_at), done last.
     */
    public function scopeOrderByUrgency($query)
    {
        return $query->orderByRaw('CASE WHEN due_date IS NOT NULL THEN 0 ELSE 1 END')
                     ->orderBy('due_date')
                     ->orderBy('created_at', 'desc')
                     ->orderBy('done');
    }

    /**
     * Scope for filtering open tasks (not done).
     */
    public function scopeOpen($query)
    {
        // return $query->where('done', false);
        return $query->where('done', false)
                     ->where(function ($q) {
                         $q->whereNull('due_date')
                           ->orWhereDate('due_date', '>=', now());
                     });
    }

    /**
     * Scope for filtering overdue tasks (past due date and not done).
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())->where('done', false);
    }
}
