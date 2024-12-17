<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Color\Rgb;

class AvatarsProvider implements Contracts\AvatarProvider
{
    const _BLACK = "000000";
    const _WHITE = "FFFFFF";
    
    private function getContrastingColor($hexcolor) {

        // определяем значения R, G и B по цвету
        $hexcolor = strlen($hexcolor) == 7 ? substr($hexcolor, 1) : $hexcolor;
        if(strlen($hexcolor) == 6){
        $r = hexdec(substr($hexcolor,0,2));
        $g = hexdec(substr($hexcolor,2,2));
        $b = hexdec(substr($hexcolor,4,2));
        return (($g > 9) OR ($r + $g + $b > 30)) ? self::_WHITE : self::_BLACK ;
        }
        // возврат по умолчанию
        return self::_BLACK;
        }
    
    public function get(Model | Authenticatable $record): string
    {
        $name = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

            $color = $record->color ? $this->getContrastingColor($record->color) : '#FFFFFF';
            $backgroundColor = $record->color ?? Rgb::fromString('rgb(' . FilamentColor::getColors()['gray'][950] . ')')->toHex();

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color='.str($color)->after('#').'&background=' . str($backgroundColor)->after('#');
    }
}
