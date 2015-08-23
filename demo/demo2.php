<?php
/**
 * Demo drawing #2
 */

require dirname(__FILE__) . '/../Color.php';
require dirname(__FILE__) . '/../LineType.php';
require dirname(__FILE__) . '/../Creator.php';

use adamasantares\dxf\Creator;
use adamasantares\dxf\Color;
use adamasantares\dxf\LineType;

(new Creator())
    ->setColor(Color::rgb(0, 100, 0))
    ->setLineType(LineType::DASHDOTX2)
    ->addCircle(0, 0, 0, 33)
    ->setLayer('poly', Color::MAGENTA, LineType::SOLID)
    ->addPolyline2d([
        100, 100,
        100, 50,
        50, 50,
        50, 100,
        30, 100,
        30, 40,
        35, 40,
        35, 20,
    ])
    ->saveToFile(dirname(__FILE__) . '/demo2.dxf');

exit("   Done (" . dirname(__FILE__) . "/demo2.dxf)\n");


