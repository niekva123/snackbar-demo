<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Snackbar
 *
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Snackbar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Snackbar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Snackbar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Snackbar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Snackbar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Snackbar whereUuid($value)
 * @mixin \Eloquent
 */
class Snackbar extends Model
{
    use HasFactory;
}
