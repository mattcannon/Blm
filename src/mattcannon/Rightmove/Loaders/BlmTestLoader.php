<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2014 Matt Cannon
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH
 * THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace mattcannon\Rightmove\Loaders;

/**
 * Class BlmTestLoader
 * @package mattcannon\Rightmove\Loaders
 */
class BlmTestLoader implements \mattcannon\Rightmove\Interfaces\BlmLoaderInterface
{
    /**
     * @return string contents of blm
     */
    public function getBlmContents()
    {
        // TODO: Implement getBlmContents() method.
        return <<<BLM
#HEADER#
VERSION : 3i
EOF : '|'
EOR : '~'

#DEFINITION#
AGENT_REF|HOUSE_NAME_NUMBER|STREET_NAME|OS_TOWN_CITY|OS_REGION|ZIPCODE|COUNTRY_CODE|FEATURE1|FEATURE2|FEATURE3|FEATURE4|FEATURE5|FEATURE6|FEATURE7|FEATURE8|FEATURE9|FEATURE10|SUMMARY|DESCRIPTION|CREATE_DATE|UPDATE_DATE|BRANCH_ID|STATUS_ID|BEDROOMS|PRICE|PRICE_QUALIFIER|NEW_HOME_FLAG|PROP_SUB_ID|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TRANS_TYPE_ID|DEVELOPMENT_NAME|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_IMAGE_02|MEDIA_IMAGE_03|MEDIA_IMAGE_04|MEDIA_IMAGE_05|MEDIA_IMAGE_06|MEDIA_IMAGE_07|MEDIA_IMAGE_08|MEDIA_IMAGE_09|MEDIA_VIRTUAL_TOUR_00|~

#DATA#
37565_1160298|Panorama Beach |Sunny Beach |NESSEBAR|BURGAS||BG|||||||||||A fully furnished studio apartment in the Panorama Beach complex benefiting from on site facilities including restaurant and bar, spa, supermarket, banks, separate swimming pools for adults and chi...|A fully furnished studio apartment in the Panorama Beach complex benefiting from on site facilities including restaurant and bar, spa, supermarket, banks, separate swimming pools for adults and children and 24 hour security. Further benefits include South Coast beach situated just 200 metres away and Burgas International Airport is approximately 23km away.<br/> <BR><BR><B>FEATURES</B><BR><BR><LI>WIDE CHOICE OF SHOPS & RESTAURANTS<LI>ONE OF THE OLDEST TOWNS IN EUROPE<LI>OLD TOWN IS PROTECTED AS A UNESCO WORLD HERITAGE SITE<LI>MOST FAVOURED BY TOURISTS ON THE SOUTHERN COAST<LI>NESSEBAR BOASTS FOUR MILES OF GOLDEN SANDY BEACHES WITH BLUE FLAG AWARD STATUS<BR><BR><br><br><p><b>Lounge/Bedroom:</b><br>laminate flooring, painted walls,air conditioning unit, PVC joinery.<br/></p><p><b>Kitchen:</b><br>laminate flooring, painted walls, tap with a counter, inside doors MDF, front/entrance doors - MDF, option for cable television and one telephone.<br/></p><p><b>Bathroom:</b><br>terracotta flooring, tiled walls, low level WC, wash hand basin with mixer tap, shower cubicle, electric boiler.<br/></p><p><b>Terrace:</b><br>approx 6.72 square metres, roof terrace/garden overlooking ocean and swimming pool.<br/></p>|2008-03-16 21:29:36|2008-03-15 16:28:36|37565|0|0|53,366||N|8|Panorama Beach  ,Sunny Beach  ,Nessebar ,Burgas ,Bulgaria|1|||0|2||1||37565_1160298_IMG_00.jpg|37565_1160298_IMG_01.jpg|37565_1160298_IMG_02.jpg|||||||||~
#END#
BLM;
    }

}
