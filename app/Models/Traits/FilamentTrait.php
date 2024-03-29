<?php
/**
 *  Generated with: https://github.com/kamansoft/vemto-filament-plugin
 *  used files template :
 *  by: kamansoft.com
 */
namespace App\Models\Traits;

trait FilamentTrait
{
    /*
     * Returns whether the user is allowed to access Filament
     */
    public function canAccessFilament(): bool
    {
        return $this->isSuperAdmin();
    }
}
