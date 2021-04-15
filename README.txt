OpenCart Color/Size Attribute Variant Product Switcher extension

* Product page add variant dropdown, changing variant will switch to corresponding product
* Use OpenCart Events System, no ocmod or vcmod, no core file modified, no schema changed
* Compatible with OpenCart 2.2+
* Tested on OpenCart 2.3.0.2 and OpenCart 3.0.3.7

Installation:

* Unzip the file and copy all contents inside "upload" folder to the root of your OpenCart installation. 
  This will not replace any existing OpenCart files unless you have a previous version of "Color/Size Attribute Variant Product Switcher" extension already installed.

Setup:
* Go to your OpenCart Admin 
  * navigate to "Catalog > Attributes -> Attribute Groups".
    * Add Color Group if not exists
    * Add Size Group if not exists
  * navigate to "Catalog > Attributes -> Attributes".
    * Add Colors to Color Group if not exists
    * Add Sizes to Size Group if not exists
  * navigate to "Catalog > Products".
    * for product require Color/Size attribute switch
      * Add Color Attribute
      * Add Size Attribute
      * Add same Model for all related variants 
  * navigate to "Extensions > Extensions > Modules".
    * Click Install next to the "Color/Size Attribute Variant Product Switcher" module.
    * Click Edit
    * Enable the extension, if not enabled
    * Select Attribute Group for Color
    * Select Attribute Group for Size
    * Click Save to activate your changes
