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
 * @see About DXF structure http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-235B22E0-A567-4CF6-92D3-38A2306D73F3.htm
 * @see ENTITIES Section http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-7D07C886-FD1D-4A0C-A7AB-B4D21F18E484.htm
 * @see Common Symbol Table Group Codes http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-8427DD38-7B1F-4B7F-BF66-21ADD1F41295.htm
 *
 * @example <code>
 *     $dxf = new \adamasantares\dxf\Creator( \adamasantares\dxf\Creator::INCHES );
 *     $dxf->addText(26, 46, 0, 'DXF testing', 8)
 *     ->setLayer('cyan', $color::CYAN)
 *     ->addLine(25, 0, 0, 100, 0, 0)
 *     ->addLine(100, 0, 0, 100, 75, 0)
 *     ->addLine(75, 100, 0, 0, 100, 0)
 *     ->addLine(0, 100, 0, 0, 25, 0)
 *     ->setLayer('blue', $color::BLUE, $ltype::DASHDOT)
 *     ->addCircle(0, 0, 0, 25)
 *     ->setLayer('custom', $color::rgb(10, 145, 230), $ltype::DASHED)
 *     ->addCircle(100, 100, 0, 25)
 *     ->setLayer('red', $color::RED)
 *     ->addArc(0, 100, 0, 25, 0.0, 270.0)
 *     ->setLayer('magenta', $color::MAGENTA)
 *     ->addArc(100, 0, 0, 25, 180.0, 90.0)
 *     ->setLayer('black')
 *     ->addPoint(0, 0, 0)
 *     ->addPoint(0, 100, 0)
 *     ->addPoint(100, 100, 0)
 *     ->addPoint(100, 0, 0)
 *     ->saveToFile('demo.dxf');
 * </code>
 */

namespace adamasantares\dxf;


/**
 * Class Creator
 * @package adamasantares\dxf
 */
class Creator {

    // units codes
    const UNITLESS = 0;
    const INCHES = 1;
    const FEET = 2;
    const MILES = 3;
    const MILLIMETERS = 4;
    const CENTIMETERS = 5;
    const METERS = 6;
    const KILOMETERS = 7;
    const MICROINCHES = 8;
    const MILS = 9;
    const YARDS = 10;
    const ANGSTROMS = 11;
    const NANOMETERS = 12;
    const MICRONS = 13;
    const DECIMETERS = 14;
    const DECAMETERS = 15;
    const HECTOMETERS = 16;
    const GIGAMETERS = 17;
    const ASTRONOMICAL_UNITS = 18;
    const LIGHT_YEARS = 19;
    const PARSECS = 20;

    /**
     * @var null Last error description
     */
    private $error = '';

    /**
     * @var array Layers collection
     */
    private $layers = [];

    private $lTypes = [];

    private $textStyles = [];

    private $textStyleName = 'STANDARD';

    /**
     * Current layer name
     * @var int
     */
    private $layerName = '0';

    /**
     * @var array Shapes collection
     */
    private $shapes = [];

    /**
     * @var array Center offset
     */
    private $offset = [0, 0, 0];

    /**
     * @var int Units
     */
    private $units = 0;


    /**
     * @var string
     * A handle is a hexadecimal number that is a unique tag for each entity in a
     * drawing or DXF file. There must be no duplicate handles. The variable
     * HANDSEED must be larger than the largest handle in the drawing or DXF file.
     * @see https://forums.autodesk.com/t5/autocad-2000-2000i-2002-archive/what-is-the-handle-in-a-dxf-entity/td-p/118936
     */
    private $handleNumber = 0x4ff;


    /**
     * @param int $units (MILLIMETERS as default value)
     * Create new DXF document
     */
    function __construct($units = self::MILLIMETERS)
    {
        $this->units = $units;
        // add default layout
        $this->addLayer($this->layerName);
    }


    /**
     * Add new layer to document
     * @param string $name
     * @param int $color Color code (@see adamasantares\dxf\Color class)
     * @param string $lineType Line type (@see adamasantares\dxf\LineType class)
     * @return Creator Instance
     */
    public function addLayer($name, $color = Color::GRAY, $lineType = LineType::SOLID)
    {
        $this->layers[$name] = [
            'color' => $color,
            'lineType' => $lineType
        ];
        $this->lTypes[$lineType] = $lineType;
        return $this;
    }


    /**
     * Sets current layer for drawing. If layer not exists than it will be created.
     * @param $name
     * @param int $color  (optional) Color code. Only for new layer (@see adamasantares\dxf\Color class)
     * @param string $lineType (optional) Only for new layer
     * @return Creator Instance
     */
    public function setLayer($name, $color = Color::GRAY, $lineType = LineType::SOLID)
    {
        if (!isset($this->layers[$name])) {
            $this->addLayer($name, $color, $lineType);
        }
        $this->layerName = $name;
        return $this;
    }


    /**
     * Returns current layer name
     */
    public function getLayer()
    {
        $this->layerName;
    }


    /**
     * Change color for current layer
     * @param int $color See adamasantares\dxf\Color constants
     * @return Creator Instance
     */
    public function setColor($color)
    {
        $this->layers[$this->layerName]['color'] = $color;
        return $this;
    }


    /**
     * Change line type for current layer
     * @param int $lineType See adamasantares\dxf\LineType constants
     * @return Creator Instance
     */
    public function setLineType($lineType)
    {
        $this->layers[$this->layerName]['lineType'] = $lineType;
        $this->lTypes[$lineType] = $lineType;
        return $this;
    }

    /**
     * Sets current style for drawing. If style does not exist then it will be created.
     * @param string $params [name, font]
     * @return Creator Instance
     */
    public function setTextStyle($name, $font, $stdFlags = 0, $fixedHeight = 0, $widthFactor = 0, $obliqueAngle = 0, $textGenerationFlags = 0, $lastHeightUsed = 0, $bigFont = null)
    {
        if ( !isset($this->textStyles[$name]) ) {
            $this->textStyles[$name] = [
                'name' => $name,
                'font' => $font,
                'stdFlags' => $stdFlags,
                'fixedHeight' => $fixedHeight,
                'widthFactor' => $widthFactor,
                'obliqueAngle' => $obliqueAngle,
                'textGenerationFlags' => $textGenerationFlags,
                'lastHeightUsed' => $lastHeightUsed,
                'bigFont' => $bigFont,
            ];
        }
        $this->textStyleName = $name;
        return $this;
    }


    /**
     * Returns current style name
     */
    public function getTextStyle()
    {
        return $this->textStyleName;
    }

    private function getEntityHandle()
    {
        $this->handleNumber++;
        return dechex($this->handleNumber);
    }


    /**
     * Add point to current layout
     * @param float $x
     * @param float $y
     * @param float $z
     * @return Creator Instance
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-9C6AD32D-769D-4213-85A4-CA9CCB5C5317.htm
     */
    public function addPoint($x, $y, $z)
    {
        $x += $this->offset[0];
        $y += $this->offset[1];
        $z += $this->offset[2];
        $this->shapes[] = "POINT\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbPoint)
            "AcDbPoint\n" .
            "10\n" . // X value
            "{$x}\n" .
            "20\n" . // Y value
            "{$y}\n" .
            "30\n" . // Z value
            "{$z}\n" .
            "0\n";
        return $this;
    }


    /**
     * Add line to current layout
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $x2
     * @param float $y2
     * @param float $z2
     * @return Creator Instance
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-FCEF5726-53AE-4C43-B4EA-C84EB8686A66.htm
     */
    public function addLine($x, $y, $z, $x2, $y2, $z2)
    {
        $x += $this->offset[0];
        $y += $this->offset[1];
        $z += $this->offset[2];
        $x2 += $this->offset[0];
        $y2 += $this->offset[1];
        $z2 += $this->offset[2];
        $this->shapes[] = "LINE\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" .
            "AcDbLine\n" . // Subclass marker (AcDbLine)
            "10\n" . // Start point X
            "{$x}\n" .
            "20\n" . // Start point Y
            "{$y}\n" .
            "30\n" . // Start point Z
            "{$z}\n" .
            "11\n" . // End point X
            "{$x2}\n" .
            "21\n" . // End point Y
            "{$y2}\n" .
            "31\n" . // End point Z
            "{$z2}\n" .
            "0\n";
        return $this;
    }

    /**
     * Add a solid to the current layout
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $w
     * @param float $h
     * @return Creator $this
     * @see http://help.autodesk.com/view/ACD/2016/ENU/?guid=GUID-E0C5F04E-D0C5-48F5-AC09-32733E8848F2
     */
    public function addSolid($x, $y, $z=0.0, $w=0.0, $h=0.0)
    {
        $y1 = $y+$h;
        $x1 = $x+$w;
        $this->shapes[] = "SOLID\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbTrace)
            "AcDbTrace\n" .
            "10\n" . // First corner, X
            "{$x}\n" .
            "20\n" . // First corner, Y
            "{$y}\n" .
            "30\n" . // First corner, Z
            "{$z}\n" .
            "11\n" . // Second corner, X
            "{$x}\n" .
            "21\n" . // Second corner, Y
            "{$y1}\n" .
            "31\n" . // Second corner, Z
            "{$z}\n" .
            "12\n" . // Third corner, X
            "{$x1}\n" .
            "22\n" . // Third corner, Y
            "{$y1}\n" .
            "32\n" . // Third corner, Z
            "{$z}\n" .
            "13\n" . // Fourth corner, X
            "{$x1}\n" .
            "23\n" . // Fourth corner, Y
            "{$y}\n" .
            "33\n" . // Fourth corner, Z
            "{$z}\n" .
            "39\n" . // Thickness
            "0\n" .
            "210\n". // Extrusion Direction, X
            "0\n" .
            "220\n". // Extrusion Direction, Y
            "0\n" .
            "230\n". // Extrusion Direction, Z
            "1\n" .
            "0\n";
        return $this;
    }

    /**
     * Add text to current layer
     * @param float $x
     * @param float $y
     * @param float $z
     * @param string $text
     * @param float $textHeight Text height
     * @param integer $position Position of text from point: 1 = top-left; 2 = top-center; 3 = top-right; 4 = center-left; 5 = center; 6 = center-right; 7 = bottom-left; 8 = bottom-center; 9 = bottom-right
     * @param float $angle Angle of text in degrees (rotation)
     * @param integer $thickness
     * @return Creator Instance
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-62E5383D-8A14-47B4-BFC4-35824CAE8363.htm
     */
    public function addText($x, $y, $z, $text, $textHeight, $position = 7, $angle = 0.0, $thickness = 0)
    {
        $positions = [
            1 => [3, 0], // top-left
            2 => [3, 1], // top-center
            3 => [3, 2], // top-right
            4 => [2, 0], // center-left
            5 => [2, 1], // center
            6 => [2, 2], // center-right
            7 => [1, 0], // bottom-left
            8 => [1, 1], // bottom-center
            9 => [1, 2]  // bottom-right
        ];
        $x += $this->offset[0];
        $y += $this->offset[1];
        $z += $this->offset[2];
        $angle = deg2rad($angle);
        $verticalJustification = $positions[$position][0];
        $horizontalJustification = $positions[$position][1];
        $this->shapes[] = "TEXT\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbText)
            "AcDbText\n" .
            "39\n" . // Thickness (optional; default = 0)
            "{$thickness}\n" .
            "10\n" . // First alignment point, X value
            "{$x}\n" .
            "20\n" . // First alignment point, Y value
            "{$y}\n" .
            "30\n" . // First alignment point, Z value
            "{$z}\n" .
            "40\n" . // Text height
            "{$textHeight}\n" .
            "1\n" . // Default value (the string itself)
            "{$text}\n" .
            "50\n" . // Text rotation (optional; default = 0)
            "{$angle}\n" .
            "41\n" . // Relative X scale factor—width (optional; default = 1)
            "1\n" .
            "51\n" . // Oblique angle (optional; default = 0)
            "0\n" .
            "7\n" . // Text style name (optional, default = STANDARD)
            "{$this->textStyleName}\n" .
            "71\n" . // Text generation flags (optional, default = 0)
            "0\n" .
            "72\n" . // Horizontal text justification type (optional, default = 0) integer codes (not bit-coded): 0 = Left, 1= Center, 2 = Right, 3 = Aligned, 4 = Middle, 5 = Fit
            "{$horizontalJustification}\n" .
            "11\n" . // Second alignment point, X value
            "{$x}\n" .
            "21\n" . // Second alignment point, Y value
            "{$y}\n" .
            "31\n" . // Second alignment point, Z value
            "{$z}\n" .
            "100\n" . // Subclass marker (AcDbText)
            "AcDbText\n" .
            "73\n" . // Vertical text justification type (optional, default = 0): integer codes (not bit-coded): 0 = Baseline, 1 = Bottom, 2 = Middle, 3 = Top
            "{$verticalJustification}\n" .
            "0\n";
        return $this;
    }


    /**
     * Add circle to current layer
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $radius
     * @return Creator Instance
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-8663262B-222C-414D-B133-4A8506A27C18.htm
     */
    public function addCircle($x, $y, $z, $radius)
    {
        $x += $this->offset[0];
        $y += $this->offset[1];
        $z += $this->offset[2];
        $this->shapes[] = "CIRCLE\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbCircle)
            "AcDbCircle\n" .
            "10\n" . // Center point, X value
            "{$x}\n" .
            "20\n" . // Center point, Y value
            "{$y}\n" .
            "30\n" . // Center point, Z value
            "{$z}\n" .
            "40\n" . // Radius
            "{$radius}\n" .
            "0\n";
        return $this;
    }


    /**
     * Add Arc to current layer.
     * Don't forget: it's drawing by counterclock-wise.
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $radius
     * @param float $startAngle
     * @param float $endAngle
     * @return $this
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-0B14D8F1-0EBA-44BF-9108-57D8CE614BC8.htm
     */
    public function addArc($x, $y, $z, $radius, $startAngle = 0.1, $endAngle = 90.0)
    {
        $x += $this->offset[0];
        $y += $this->offset[1];
        $z += $this->offset[2];
        $this->shapes[] = "ARC\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbCircle)
            "AcDbCircle\n" .
            "39\n" . // Thickness (optional; default = 0)
            "0\n" .
            "10\n" . // Center point, X value
            "{$x}\n" .
            "20\n" . // Center point, Y value
            "{$y}\n" .
            "30\n" . // Center point, Z value
            "{$z}\n" .
            "40\n" . // Radius
            "{$radius}\n" .
            "100\n" . // Subclass marker (AcDbArc)
            "AcDbArc\n" .
            "50\n" . // Start angle
            "{$startAngle}\n" .
            "51\n" . // End angle
            "{$endAngle}\n" .
            "0\n";
        return $this;
    }


    /**
     * Add Ellipse to current layer.
     * @param float $cx Center Point X
     * @param float $cy Center Point Y
     * @param float $cz Center Point Z
     * @param float $mx Major Axis Endpoint X
     * @param float $my Major Axis Endpoint Y
     * @param float $mz Major Axis Endpoint Z
     * @param float $ratio Ratio of minor axis to major axis
     * @return $this
     * @see https://raw.githubusercontent.com/active-programming/DXF-Creator-for-PHP/master/demo/ellipse2.png
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-107CB04F-AD4D-4D2F-8EC9-AC90888063AB.htm
     */
    public function addEllipse($cx, $cy, $cz, $mx, $my, $mz, $ratio=0.5, $start = 0, $end = 6.283185307179586)
    {
        $mx -= $cx;
        $my -= $cy;
        $mz -= $cz;
        $this->shapes[] = "ELLIPSE\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbEllipse)
            "AcDbEllipse\n" .
            "10\n" . // Center point, X value
            "{$cx}\n" .
            "20\n" . // Center point, Y value
            "{$cy}\n" .
            "30\n" . // Center point, Z value
            "{$cz}\n" .
            "11\n" . // Endpoint of major axis, X value
            "{$mx}\n" .
            "21\n" . // Endpoint of major axis, Y value
            "{$my}\n" .
            "31\n" . // Endpoint of major axis, Z value
            "{$mz}\n" .
            "40\n" . // Ratio of minor axis to major axis
            "{$ratio}\n" .
            "41\n" . // Start parameter (this value is 0.0 for a full ellipse)
            "{$start}\n" .
            "42\n" . // End parameter (this value is 2pi for a full ellipse)
            "{$end}\n" .
            "0\n";
        return $this;
    }


    /**
     * Add Ellipse to current layer.
     * @param float $cx Center Point X
     * @param float $cy Center Point Y
     * @param float $cz Center Point Z
     * @param float $mx Major Axis Endpoint X
     * @param float $my Major Axis Endpoint Y
     * @param float $mz Major Axis Endpoint Z
     * @param float $rx Minor Axis Endpoint X
     * @param float $ry Minor Axis Endpoint Y
     * @param float $rz Minor Axis Endpoint Z
     *
     * @return $this
     * @see https://raw.githubusercontent.com/active-programming/DXF-Creator-for-PHP/master/demo/ellipse.png
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-107CB04F-AD4D-4D2F-8EC9-AC90888063AB.htm
     */
    public function addEllipseBy3Points($cx, $cy, $cz, $mx, $my, $mz, $rx, $ry, $rz, $start = 0, $end = 6.283185307179586)
    {
        $length1 = sqrt(pow($cx - $mx, 2) + pow($cy - $my, 2) + pow($cz - $mz, 2));
        $length2 = sqrt(pow($cx - $rx, 2) + pow($cy - $ry, 2) + pow($cz - $rz, 2));
        $ratio = round($length2 / $length1, 3);
        $mx -= $cx;
        $my -= $cy;
        $mz -= $cz;
        $this->shapes[] = "ELLIPSE\n" .
            "5\n" . // Entity Handle
            "{number}\n" .
            "100\n" . // Subclass marker (AcDbEntity)
            "AcDbEntity\n" .
            "8\n" . // Layer name
            "{$this->layerName}\n" .
            "100\n" . // Subclass marker (AcDbEllipse)
            "AcDbEllipse\n" .
            "10\n" . // Center point, X value
            "{$cx}\n" .
            "20\n" . // Center point, Y value
            "{$cy}\n" .
            "30\n" . // Center point, Z value
            "{$cz}\n" .
            "11\n" . // Endpoint of major axis, X value
            "{$mx}\n" .
            "21\n" . // Endpoint of major axis, Y value
            "{$my}\n" .
            "31\n" . // Endpoint of major axis, Z value
            "{$mz}\n" .
            "40\n" . // Ratio of minor axis to major axis
            "{$ratio}\n" .
            "41\n" . // Start parameter (this value is 0.0 for a full ellipse)
            "{$start}\n" .
            "42\n" . // End parameter (this value is 2pi for a full ellipse)
            "{$end}\n" .
            "0\n";
        return $this;
    }


    /**
     * Add polyline to current layer.
     * @param array[float] $points Points array: [x, y, x2, y2, x3, y3, ...]
     * @param int $flag Polyline flag (bit-coded); default is 0: 1 = Closed; 128 = Plinegen
     * @return $this
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-ABF6B778-BE20-4B49-9B58-A94E64CEFFF3.htm
     */
    public function addPolyline($points, $flag = 0)
    {
        $count = count($points);
        if ($count > 2 && ($count % 2) == 0) {
            $dots = ($count / 2 + 1);
            $polyline = "LWPOLYLINE\n" .
                "5\n" . // Entity Handle
                "{number}\n" .
                "100\n" . // Subclass marker (AcDbEntity)
                "AcDbEntity\n" .
                "8\n" . // Layer name
                "{$this->layerName}\n" .
                "100\n" . // Subclass marker (AcDbPolyline)
                "AcDbPolyline\n" .
                "90\n" . // Number of vertices
                "{$dots}\n" .
                "70\n" . // Polyline flag (bit-coded); default is 0: 1 = Closed; 128 = Plinegen
                "{$flag}\n" .
                "43\n" . // Constant width (optional; default = 0).
                "0\n" .
                "38\n" . // Elevation (optional; default = 0)
                "0\n" .
                "39\n" . // Thickness (optional; default = 0)
                "0\n";
            for ($i = 0; $i < $count; $i += 2) {
                $x = $points[$i] + $this->offset[0];
                $y = $points[$i+1] + $this->offset[1];
                $polyline .=
                    "10\n" .
                    "{$x}\n" .
                    "20\n" .
                    "{$y}\n";
            }
            $this->shapes[] = $polyline . "0\n";
        }
        return $this;
    }


    /**
     * Add 3D polyline to current layer.
     * @param array[float] $points Points array: [x, y, z, x2, y2, z2, x3, y3, z3, ...]
     * @return $this
     * @deprecated It was mistake, the polyline has no Z coordinate point (code 30)
     */
    public function addPolyline2d($points)
    {
        return $this->addPolyline($points);
    }


    /**
     * Returns last error
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Set offset
     * @param $x
     * @param $y
     * @param $z
     */
    public function setOffset($x, $y, $z = 0)
    {
        $this->offset = [$x, $y, $z];
    }


    /**
     * Get offset
     * @return array
     */
    public function getOffset()
    {
        return $this->offset;
    }


    /**
     * Save DXF document to file
     * @param string $fileName
     * @return bool True on success
     */
    function saveToFile($fileName)
    {
        $this->error = '';
        $dir = dirname($fileName);
        if (!is_dir($dir)) {
            $this->error = "Directory not exists: {$dir}";
            return false;
        }
        if (!file_put_contents($fileName, $this->getString())) {
            $this->error = "Error on save: {$fileName}";
            return false;
        }
        return true;
    }


    /**
     * Send DXF document to browser
     * @param string $fileName
     * @param bool $stop Set to FALSE if no need to exit from script
     */
    public function sendAsFile($fileName, $stop = true)
    {
        while (false !== ob_get_clean()) { };
        header("Content-Type: image/vnd.dxf");
        header("Content-Disposition: inline; filename={$fileName}");
        echo $this->getString();
        if ($stop) {
            exit;
        }
    }


    /**
     * Returns DXF document as string
     * @return string DXF document
     */
    private function getString()
    {
        $template = file_get_contents(__DIR__ . '/template.dxf');
        $lTypes = $this->getLtypesString();
        $layers = $this->getLayersString();
        $textStyles = $this->getTextStylesString();
        $entities = $this->getEntities();
        $dxf = str_replace([
            '{LTYPES_TABLE}',
            '{LAYERS_TABLE}',
            '{STYLES_TABLE}',
            '{ENTITIES_SECTION}'
        ], [
            $lTypes,
            $layers,
            $textStyles,
            $entities
        ], $template);
        return  $dxf;
    }


    private function getEntities()
    {
        foreach ($this->shapes as &$shape) {
            $shape = str_replace('{number}', $this->getEntityHandle(), $shape);
        }
        $entities = implode('', $this->shapes);
        return rtrim($entities, "\n");
    }


    /**
     * Generates LTYPE items
     * @return string
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-F57A316C-94A2-416C-8280-191E34B182AC.htm
     * @see https://ezdxf.readthedocs.io/en/latest/dxfinternals/linetype_table.html
     */
    private function getLtypesString()
    {
        $ownerHandle = $this->getEntityHandle();
        $lTypes = "LTYPE\n5\n{$ownerHandle}\n330\n0\n100\nAcDbSymbolTable\n70\n4\n0\n" .
            "LTYPE\n5\n" . $this->getEntityHandle() . "\n330\n{$ownerHandle}\n100\nAcDbSymbolTableRecord\n100\nAcDbLinetypeTableRecord\n2\nByBlock\n70\n0\n3\n\n72\n65\n73\n0\n40\n0\n0\n" .
            "LTYPE\n5\n" . $this->getEntityHandle() . "\n330\n{$ownerHandle}\n100\nAcDbSymbolTableRecord\n100\nAcDbLinetypeTableRecord\n2\nByLayer\n70\n0\n3\n\n72\n65\n73\n0\n40\n0\n0\n";
        foreach ($this->lTypes as $type) {
            $number = $this->getEntityHandle();
            $name = isset(LineType::$lines[$type]) ? LineType::$lines[$type][0] : '';
            $pattern = isset(LineType::$lines[$type][1]) ? LineType::$lines[$type][1] : "73\n0\n40\n0.0";
            $lTypes .= "LTYPE\n" .
                "5\n" . // Handle
                "{$number}\n" .
                "330\n" . // Soft-pointer ID/handle to owner object
                "{$ownerHandle}\n" .
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
                "{$pattern}\n" .
                "0\n";
        }
        return rtrim($lTypes, "\n");
    }


    /**
     * Generates LAYERS
     * @return string
     * @see http://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-D94802B0-8BE8-4AC9-8054-17197688AFDB.htm
     */
    private function getLayersString()
    {
        $ownerNumber = $this->getEntityHandle();
        $layers = "LAYER\n5\n{$ownerNumber}\n330\n0\n100\nAcDbSymbolTable\n70\n1\n0\n";
        if (count($this->layers) > 0) {
            foreach ($this->layers as $name => $layer) {
                $number = $this->getEntityHandle();
                $layers .= "LAYER\n" .
                    "5\n" .
                    "{$number}\n" .
                    "330\n" .
                    "{$ownerNumber}\n" .
                    "100\n" . // Subclass marker
                    "AcDbSymbolTableRecord\n" .
                    "100\n" . // Subclass marker
                    "AcDbLayerTableRecord\n" .
                    "2\n" .
                    "{$name}\n" . // Layer name
                    "70\n" . // Standard flags (bit-coded values)
                    "64\n" .
                    "62\n" . // Color number (if negative, layer is off)
                    "{$layer['color']}\n" .
                    "6\n" . // Linetype name
                    "{$layer['lineType']}\n" .
                    "390\n" .
                    "F\n" .
                    "0\n";
            }
        }
        return rtrim($layers, "\n");
    }

    /**
     * Generates TEXTSTYLES
     * @return string
     * @see https://help.autodesk.com/cloudhelp/2016/ENU/AutoCAD-DXF/files/GUID-EF68AF7C-13EF-45A1-8175-ED6CE66C8FC9.htm
     */
    private function getTextStylesString()
    {
        $ownerNumber = $this->getEntityHandle();
        $textStyles = "STYLE\n5\n{$ownerNumber}\n330\n0\n100\nAcDbSymbolTable\n70\n3\n0\n";

        if (count($this->textStyles) > 0) {
            foreach ($this->textStyles as $name => $style) {
                $number = $this->getEntityHandle();
                $textStyles .= "STYLE\n" .
                    "5\n" .
                    "{$number}\n" .
                    "330\n" .
                    "{$ownerNumber}\n" .
                    "100\n" . // Subclass marker
                    "AcDbSymbolTableRecord\n" . // Subclass marker value
                    "100\n" . // Subclass marker group code
                    "AcDbTextStyleTableRecord\n" . // Subclass marker value
                    "2\n" . // Style name group code
                    "{$style['name']}\n" . // Style name value
                    "70\n" . // Standard flags group code
                    "{$style['stdFlags']}\n" . // Standard flags values
                    "40\n" . // Fixed text height group code
                    "{$style['fixedHeight']}\n" . // Fixed text height value;
                    "41\n" . // Width factor group code
                    "{$style['widthFactor']}\n" . // Width factor value
                    "50\n" . // Oblique angle group code
                    "{$style['obliqueAngle']}\n" . // Oblique angle value
                    "71\n" . // Text generation flags group code
                    "{$style['textGenerationFlags']}\n" . // Text generation flags value; 2 = Text is backward (mirrored in X); 4 = Text is upside down (mirrored in Y)
                    "42\n" . // Last height used group code
                    "{$style['lastHeightUsed']}\n" . // Last height used value
                    "3\n" . // Primary font file name group code
                    "{$style['font']}\n" . // Primary font file name value
                    "4\n" . // Bigfont file name group code
                    "{$style['bigFont']}\n"; // Bigfont file name value; blank if none
            }

            $textStyles .= "0\n";
        }
        return rtrim($textStyles, "\n");
    }
    
    public function __toString(){
        return $this->getString();
    }

}
