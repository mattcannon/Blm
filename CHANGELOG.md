#Version 0.2.0 - DEV

##Added

* interfaces showing public API for
    * PropertyObject
    * Parser
* hips
* parsing of strings as well as files.
* media documents   
* new methods on Parser:
    * setBlmContents();
    * getBlmContents();
    * setBlmFilePath();
    * getBlmFilePath();
    
* new properties on PropertyObject:
    * $statusId
    * $priceQualifier
    * $publishedFlag
    * $letTypeId
    * $letFurnId
    * $letRentFrequency
    * $tenureTypeId
    * $transTypeId
* new Methods on PropertyObject:
    * toArray();
    * getStatusId();
    * getPriceQualifier();
    * getPublishedFlag();
    * getLetTypeId();
    * getLetFurnId();
    * getLetRentFrequency();
    * getTenureTypeId();
    * getTransTypeId();
##Changed
* parseFile -> parseBlm
