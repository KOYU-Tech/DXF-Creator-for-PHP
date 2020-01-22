<?php
/**
 * Demo drawing #1
 */

require dirname(__FILE__) . '/../Color.php';
require dirname(__FILE__) . '/../LineType.php';
require dirname(__FILE__) . '/../Creator.php';

use adamasantares\dxf\Creator;
use adamasantares\dxf\Color;
use adamasantares\dxf\LineType;

$dxf = new Creator();
$dxf->setTextStyle('Consolas Regular', 'consola')
    ->addText(50, 50, 0, 'DXF testing', 8, 5)
    ->setLayer('cyan', Color::CYAN)
    ->addLine(25, 0, 0, 100, 0, 0)
    ->addLine(100, 0, 0, 100, 75, 0)
    ->addLine(75, 100, 0, 0, 100, 0)
    ->addLine(0, 100, 0, 0, 25, 0)
    ->setLayer('blue', Color::BLUE, LineType::DASHDOT)
    ->addCircle(0, 0, 0, 25)
    ->setLayer('custom', Color::rgb(10, 145, 230))//, LineType::DASHED)
    ->addCircle(100, 100, 0, 25)
    ->setLayer('red', Color::RED)
    ->addArc(0, 100, 0, 25, 0.0, 270.0)
    ->setLayer('magenta', Color::MAGENTA)
    ->addArc(100, 0, 0, 25, 180.0, 90.0)
    ->setLayer('black')
    ->addPoint(0, 0, 0)
    ->addPoint(0, 100, 0)
    ->addPoint(100, 100, 0)
    ->addPoint(100, 0, 0)
    ->saveToFile(dirname(__FILE__) . '/demo1.dxf');

exit("   Done (" . dirname(__FILE__) . "/demo1.dxf)\n");