<?php
/**
 * Original script (DXF)
 * @author Alessandro Vernassa <speleoalex@gmail.com> http://speleoalex.altervista.org
 * @copyright Copyright (c) 2013
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 *
 * Upgrade script to "Creator"
 * @author Konstantin Kutsevalov <mail@art-prog.ru>
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

}