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
    ->addEllipse(-20, 0, 0, -20, 30, 0, 0.5)
    ->setLayer('2', Color::MAGENTA, LineType::SOLID)
    ->addEllipseBy3Points(20, 0, 0, 20, 30, 0, 35, 0, 0)
    ->saveToFile(dirname(__FILE__) . '/demo3.dxf');

exit("   Done (" . dirname(__FILE__) . "/demo3.dxf)\n");


