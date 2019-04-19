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
class LineType {

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


    private static $lines = [
        self::SOLID => 'Continuous',
        self::CENTER => 'Center',
        self::CENTERX2 => 'Center (x2)',
        self::CENTER2 => 'Center (2)',
        self::DASHED => 'Dashed',
        self::DASHEDX2 => 'Dashed (x2)',
        self::DASHED2 => 'Dashed (2)',
        self::PHANTOM => 'Phantom',
        self::PHANTOMX2 => 'Phantom (x2)',
        self::PHANTOM2 => 'Phantom (2)',
        self::DASHDOT => 'Dashdot',
        self::DASHDOTX2 => 'Dashdot (x2)',
        self::DASHDOT2 => 'Dashdot (2)',
        self::DOT => 'Dot',
        self::DOTX2 => 'Dot (x2)',
        self::DOT2 => 'Dot (2)',
        self::DIVIDE => 'Divide',
        self::DIVIDEX2 => 'Divide (x2)',
        self::DIVIDE2 => 'Divide (2)',
    ];


    /**
     * @param $type string
     * @see https://www.autodesk.com/techpubs/autocad/acad2000/dxf/common_symbol_table_group_codes_dxf_04.htm
     * @see https://knowledge.autodesk.com/search-result/caas/CloudHelp/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-F57A316C-94A2-416C-8280-191E34B182AC-htm.html
     * @return string
     */
    public static function getString($type)
    {
        $name = isset(self::$lines[$type]) ? self::$lines[$type] : '';
        return "LTYPE\n" .
                "5\n" . // Handle (?)
                "14\n" .
                "330\n" . // Soft-pointer ID/handle to owner object
                "5\n" .
                "100\n" . // Subclass marker (AcDbSymbolTable)
                "AcDbSymbolTableRecord\n" .
                "100\n" .
                "AcDbLinetypeTableRecord\n" .
                "2\n" . // Linetype name
                "{$type}\n" .
                "70\n" . // Standard flag values (bit-coded values)
                "64\n" .
                "3\n" . // Descriptive text for linetype
                "{$name}\n" .
                "72\n" . // Alignment code; value is always 65, the ASCII code for A
                "65\n" .
                "73\n" . // The number of linetype elements
                "0\n" .
                "40\n" . // Total pattern length
                "0.0\n" .
                "0\n";
    }

}
