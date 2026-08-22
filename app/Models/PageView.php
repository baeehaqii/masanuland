<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One row per public page hit. Feeds the dashboard widgets.
 *
 * ponytail: own table, no analytics SDK. Swap for GA4 the day marketing needs
 * campaign attribution.
 */
class PageView extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];
}
