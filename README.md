# DXF Creator for PHP

A simple DXF creator for PHP.
This code is the upgrade of [DXF-Writer](http://www.phpclasses.org/package/7954-PHP-Generate-CAD-files-in-the-AutoCAD-DXF-format.html).

##Example

```
$dxf = new Creator();
$dxf->addText(26, 46, 0, 'DXF testing', 8)
    ->setColor(Color::CYAN) // change color of default layer
    ->addLine(25, 0, 0, 100, 0, 0)
    ->addLine(100, 0, 0, 100, 75, 0)
    ->addLine(75, 100, 0, 0, 100, 0)
    ->addLine(0, 100, 0, 0, 25, 0)
    ->setLayer('blue', Color::BLUE, LineType::DASHDOT) // create new layer
    ->addCircle(0, 0, 0, 25)
    ->setLayer('custom', Color::rgb(10, 145, 230), LineType::DASHED)
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
    ->saveToFile('demo.dxf');
```

See "demo" directory of project

##Install by Composer

```
composer require adamasantares/dxf "0.1.3"
```

or

```
"require": {
      "adamasantares/dxf": "0.1.3"
  }
```

