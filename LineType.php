<?php
/**
 * Original script (DXF)
 * @author Alessandro Vernassa <speleoalex@gmail.com> http://speleoalex.altervista.org
 * @copyright Copyright (c) 2013
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 *
 * Upgrade script to "Creator"
 * @author Konstantin Kutsevalov <adamasantares@gmail.com>
 * @since 2015/08
 *
 * @see http://www.autodesk.com/techpubs/autocad/acad2000/dxf/
 */

namespace adamasantares\dxf;


/**
 * Class LineType
 * @package adamasantares\dxf
 *
 * @see https://pythonhosted.org/dxfwrite/entities/linepattern.html#linepattern
 */
final class LineType {

    const SOLID = 'CONTINUOUS';
    const CENTER = 'CENTER';
    const CENTERX2 = 'CENTERX2';
    const CENTER2 = 'CENTER2';
    const DASHED = 'DASHED';
    const DASHEDX2 = 'DASHEDX2';
    const DASHED2 = 'DASHED2';
    const PHANTOM = 'PHANTOM';
    const PHANTOMX2 = 'PHANTOMX2';
    const PHANTOM2 = 'PHANTOM2';
    const DASHDOT = 'DASHDOT';
    const DASHDOTX2 = 'DASHDOTX2';
    const DASHDOT2 = 'DASHDOT2';
    const DOT = 'DOT';
    const DOTX2 = 'DOTX2';
    const DOT2 = 'DOT2';
    const DIVIDE = 'DIVIDE';
    const DIVIDEX2 = 'DIVIDEX2';
    const DIVIDE2 = 'DIVIDE2';


    public static $lines = [
        self::SOLID => ['Solid line', "73\n0\n40\n0.0"],
        self::CENTER => ['Center ____ _ ____ _ ____ _ ____ _ ____ _ ____', "73\n4\n40\n50.8\n49\n31.75\n74\n0\n49\n-6.35\n74\n0\n49\n6.35\n74\n0\n49\n-6.35\n74\n0"],
        self::CENTERX2 => ['Center (2x) ________  __  ________  __  _____', "73\n4\n40\n101.6\n49\n63.5\n74\n0\n49\n-12.7\n74\n0\n49\n12.7\n74\n0\n49\n-12.7\n74\n0"],
        self::CENTER2 => ['Center (.5x) ___ _ ___ _ ___ _ ___ _ ___ _ ___', "73\n4\n40\n28.575\n49\n19.05\n74\n0\n49\n-3.175\n74\n0\n49\n3.175\n74\n0\n49\n-3.175\n74\n0"],
        self::DASHED => ['Dashed _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _', "73\n2\n40\n19.05\n49\n12.7\n74\n0\n49\n-6.35\n74\n0"],
        self::DASHEDX2 => ['Dashed (2x) ____  ____  ____  ____  ____  ___', "73\n2\n40\n38.09\n49\n25.4\n74\n0\n49\n-12.7\n74\n0"],
        self::DASHED2 => ['Dashed (.5x) _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _', "73\n2\n40\n9.5249\n49\n6.35\n74\n0\n49\n-3.175\n74\n0"],
        self::PHANTOM => ['Phantom ______  __  __  ______  __  __  ______', "73\n6\n40\n12.7\n49\n6.35\n74\n0\n49\n-1.27\n74\n0\n49\n1.27\n74\n0\n49\n-1.27\n74\n0\n49\n1.27\n74\n0\n49\n-1.27\n74\n0"],

        // TODO wrong pattern
        self::PHANTOMX2 => ['Phantom (2x)____________    ____    ____    ____________', "73\n6\n40\n12.7\n49\n6.35\n74\n0\n49\n-1.27\n74\n0\n49\n1.27\n74\n0\n49\n-1.27\n74\n0\n49\n1.27\n74\n0\n49\n-1.27\n74\n0"],
        // TODO wrong pattern
        self::PHANTOM2 => ['Phantom (.5x) ___ _ _ ___ _ _ ___ _ _ ___ _ _ ___', "73\n6\n40\n12.7\n49\n6.35\n74\n0\n49\n-1.27\n74\n0\n49\n1.27\n74\n0\n49\n-1.27\n74\n0\n49\n1.27\n74\n0\n49\n-1.27\n74\n0"],

        self::DASHDOT => ['Dash dot __ . __ . __ . __ . __ . __ . __ . __', "73\n4\n40\n25.4\n49\n12.7\n74\n0\n49\n-6.35\n74\n0\n49\n0\n74\n0\n49\n-6.35\n74\n0"],
        self::DASHDOTX2 => ['Dash dot (2x) ____  .  ____  .  ____  .  ___', "73\n4\n40\n50.8\n49\n25.4\n74\n0\n49\n-12.7\n74\n0\n49\n0\n74\n0\n49\n-12.7\n74\n0"],
        self::DASHDOT2 => ['Dash dot (.5x) _._._._._._._._._._._._._._._.', "73\n4\n40\n12.7\n49\n6.35\n74\n0\n49\n-3.175\n74\n0\n49\n0\n74\n0\n49\n-3.175\n74\n0"],
        self::DOT => ['Dot . . . . . . . . . . . . . . . . . . . . . .', "73\n2\n40\n6.35\n49\n0\n74\n0\n49\n-6.35\n74\n0"],
        self::DOTX2 => ['Dot (2x) .  .  .  .  .  .  .  .  .  .  .  .  .', "73\n2\n40\n12.7\n49\n0\n74\n0\n49\n-12.7\n74\n0"],
        self::DOT2 => ['Dot (.5x) .....................................', "73\n2\n40\n3.175\n49\n0\n74\n0\n49\n-3.175\n74\n0"],
        self::DIVIDE => ['Divide ____ . . ____ . . ____ . . ____ . . ____', "73\n6\n40\n31.75\n49\n12.7\n74\n0\n49\n-6.35\n74\n0\n49\n0\n74\n0\n49\n-6.35\n74\n0\n49\n0\n74\n0\n49\n-6.35\n74\n0"],
        self::DIVIDEX2 => ['Divide (2x) ________  .  .  ________  .  .  _', "73\n6\n40\n63.5\n49\n25.4\n74\n0\n49\n-12.7\n74\n0\n49\n0\n74\n0\n49\n-12.7\n74\n0\n49\n0\n74\n0\n49\n-12.7\n74\n0"],
        self::DIVIDE2 => ['Divide (.5x) __..__..__..__..__..__..__..__.._', "73\n6\n40\n15.875\n49\n6.35\n74\n0\n49\n-3.175\n74\n0\n49\n0\n74\n0\n49\n-3.175\n74\n0\n49\n0\n74\n0\n49\n-3.175\n74\n0"],
    ];

}
