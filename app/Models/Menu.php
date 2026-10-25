<?php

namespace App\Models;

use Database\Factories\MenuFactory;
use Datlechin\FilamentMenuBuilder\Models\Menu as MenuBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Menu extends MenuBuilder
{
    /** @use HasFactory<MenuFactory> */
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the menu assigned to the given location.
     */
    public static function location(string $location): ?MenuBuilder
    {
        $menuId = Cache::rememberForever(
            self::locationCacheKey($location),
            fn (): ?string => self::query()
                ->where('is_visible', true)
                ->whereRelation('locations', 'location', $location)
                ->value('id'),
        );

        if ($menuId === null) {
            return null;
        }

        return self::query()
            ->where('is_visible', true)
            ->with('menuItems')
            ->find($menuId);
    }
}
