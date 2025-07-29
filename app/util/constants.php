<?php
// VERSION NUMBER
const VERSION = "1.1.0-dev";
const API_VERSION = 1;


// GROUPS
const GROUP_ID_ADMIN = 1;
const GROUP_ID_MEMBER = 2;
const GROUPS = [
    GROUP_ID_ADMIN => [
        'id' => GROUP_ID_ADMIN,
        'name' => 'Admin',
    ],
    GROUP_ID_MEMBER => [
        'id' => GROUP_ID_MEMBER,
        'name' => 'Member',
    ]
];

// COOKIE / SESSION NAMES
const SESS_LOGGED_IN = "loggedin";
const COOKIE_REMEMBER_ME = "rememberme";
const SESS_USER_GROUP = "usg";
const SESS_USER_ID = 'uid';
const SESS_USER_NAME = 'uname';

// GENERAL OPTIONS
const PAGE = "page";
const MAX_PER_PAGE = "maxperpage";
const SORT = "sort";
const SORT_DIRECTION = "sort_direction";
const FILTERS = "filters";
const SQL_SORT_ASC = "ASC";
const SQL_SORT_DESC = "DESC";

// PUZZLE GATEWAY
const PUZ_ID = "puzzleid";
const PUZ_NAME = "puzname";
const PUZ_PIECES = "pieces";
const PUZ_COST = "cost";
const PUZ_DATE_ACQUIRED = "dateacquired";
const PUZ_BRAND_ID = "brandid";
const PUZ_SOURCE_ID = "sourceid";
const PUZ_LOCATION_ID = "locationid";
const PUZ_DISPOSITION_ID = "dispositionid";
const PUZ_PICTURE_URL = "pictureurl";
const PUZ_UPC = "upc";
const PUZ_FIELDS = [
    PUZ_ID,
    PUZ_NAME,
    PUZ_PIECES,
    PUZ_COST,
    PUZ_DATE_ACQUIRED,
    PUZ_BRAND_ID,
    PUZ_SOURCE_ID,
    PUZ_LOCATION_ID,
    PUZ_DISPOSITION_ID,
    PUZ_PICTURE_URL,
    PUZ_UPC,
];

const PUZ_SORT_BRAND_NAME = "brand.brandname";


const PUZ_FILTER_NAME = "name";
const PUZ_FILTER_PIECES = "pieces";
const PUZ_FILTER_BRAND = "brand";
const PUZ_FILTER_COST = "cost";
const PUZ_FILTER_SOURCE = "source";
const PUZ_FILTER_LOCATION = "location";
const PUZ_FILTER_DISPOSITION = "disposition";

const PUZ_FILTERS = [
    PUZ_FILTER_NAME,
    PUZ_FILTER_PIECES,
    PUZ_FILTER_BRAND,
    PUZ_FILTER_COST,
    PUZ_FILTER_SOURCE,
    PUZ_FILTER_LOCATION,
    PUZ_FILTER_DISPOSITION,
];

// USER INVENTORY GATEWAY
const UINV_ID = "userinvid";
const UINV_STATUS = "statusid";
const UINV_MISSING = "missingpieces";
const UINV_STARTDATE = "startdate";
const UINV_ENDDATE = "enddate";
const UINV_TOTALDAYS = "totaldays";
const UINV_DIFFICULTY = "difficultyrating";
const UINV_QUALITY = "qualityrating";
const UINV_OVERALL = "overallrating";
const UINV_OWNERSHIP = "ownershipid";
const UINV_LOANED = "loanedoutto";

const UINV_FILTER_USER = "userid";
const UINV_FILTER_STATUS = "status";
const UINV_FILTER_MISSING = "missingpieces";
const UINV_FILTER_DIFFICULTY = "difficultyrating";
const UINV_FILTER_QUALITY = "qualityrating";
const UINV_FILTER_OVERALL = "overallrating";
const UINV_FILTER_OWNERSHIP = "ownership";

// USER GATEWAY
const USER_ID = 'userid';
const USER_NAME = 'user_name';
const USER_FULLNAME = 'full_name';
const USER_EMAIL = 'email';
const USER_EMAIL_CONFIRMED = 'emailconfirmed';
const USER_PASSWORD = 'user_password';
const USER_HASH = 'user_hash';
const USER_GROUP_ID = 'usergroupid';
const USER_THEME_ID = 'themeid';
const USER_LAST_LOGIN = 'lastlogin';

const USER_FILTER_NAME = 'username';
const USER_FILTER_FULLNAME = 'fullname';
const USER_FILTER_EMAIL = 'email';
const USER_FILTER_GROUP = 'group';
const USER_FILTER_THEME = 'theme';
const USER_FILTERS = [
    USER_FILTER_NAME,
    USER_FILTER_FULLNAME,
    USER_FILTER_EMAIL,
    USER_FILTER_GROUP,
    USER_FILTER_THEME,
];

// STATUS GATEWAY
const STATUS_ID = 'statusid';
const STATUS_DESCRIPTION = 'statusdesc';

// OWNERSHIP GATEWAY
const OWNERSHIP_ID = 'ownershipid';
const OWNERSHIP_DESCRIPTION = 'ownershipdesc';

// DISPOSITION GATEWAY
const DISPOSITION_ID = 'dispositionid';
const DISPOSITION_DESCRIPTION = 'dispositiondesc';

// BRAND GATEWAY
const BRAND_ID = 'brandid';
const BRAND_NAME = 'brandname';
