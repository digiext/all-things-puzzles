<?php

// HTTP REQUEST METHODS
// Somehow there is no constants for these in std php (ignoring ext-oauth)
const GET = 'GET';
const POST = 'POST';
const PUT = 'PUT';
const PATCH = 'PATCH';
const DELETE = 'DELETE';

// API FIELDS
const SERVER_VERSION = 'server_version';
const API = 'api_version';
const AUTHENTICATED = 'authenticated';
const ERROR = 'error';
const HAS_ERROR = 'has_error';
const DATA = 'data';
const PREV = 'prev';
const NEXT = 'next';
const LINK = 'link';

// API ERROR FIELDS
const ERROR_CODE = 'error_code';
const MESSAGE = 'message';
const ID = 'id';
const ACCEPTED_METHODS = 'accepted_methods';

// API ERRORS
const API_ERROR_UNAUTHORIZED = [
    ERROR_CODE => "unauthorized",
    MESSAGE => "You need correct authorization to interact with this endpoint!",
];
const API_ERROR_NO_PERMISSION = [
    ERROR_CODE => "no_permission",
    MESSAGE => "You don't have the correct permission to use this endpoint!",
];
const API_ERROR_TEAPOT = [
    ERROR_CODE => "teapot",
    MESSAGE => "I'm a teapot, short and stout. Here is my handle, here is my spout. When you hear my whistle, hear me shout: 'Tip me over and pour me out!'",
];
const API_ERROR_WRONG_METHOD = [
    ERROR_CODE => "wrong_method",
    MESSAGE => "Method not allowed",
];
const API_ERROR_DATABASE = [
    ERROR_CODE => "db_error",
    MESSAGE => "Database error. Check your query parameters.",
];
const API_ERROR_BAD_REQUEST = [
    ERROR_CODE => "bad_request",
    MESSAGE => "Bad request. Check your query parameters.",
];
const API_ERROR_INVALID_USER = [
    ERROR_CODE => "invalid_user",
    MESSAGE => "User does not exist or is invalid",
];
const API_ERROR_INVALID_PUZZLE = [
    ERROR_CODE => "invalid_puzzle",
    MESSAGE => "Puzzle does not exist or is invalid",
];
const API_ERROR_INVALID_WISHLIST_PUZZLE = [
    ERROR_CODE => "invalid_wishlist_puzzle",
    MESSAGE => "Wishlist puzzle does not exist or is invalid",
];
const API_ERROR_INVALID_STATUS = [
    ERROR_CODE => "invalid_status",
    MESSAGE => "Status does not exist or is invalid",
];
const API_ERROR_INVALID_OWNERSHIP = [
    ERROR_CODE => "invalid_ownership",
    MESSAGE => "Ownership does not exist or is invalid",
];
const API_ERROR_INVALID_DISPOSITION = [
    ERROR_CODE => "invalid_disposition",
    MESSAGE => "Disposition does not exist or is invalid",
];
const API_ERROR_INVALID_BRAND = [
    ERROR_CODE => "invalid_brand",
    MESSAGE => "Brand does not exist or is invalid",
];
const API_ERROR_INVALID_SOURCE = [
    ERROR_CODE => "invalid_source",
    MESSAGE => "Source does not exist or is invalid",
];
const API_ERROR_INVALID_LOCATION = [
    ERROR_CODE => "invalid_location",
    MESSAGE => "Location does not exist or is invalid",
];
const API_ERROR_INVALID_CATEGORY = [
    ERROR_CODE => "invalid_category",
    MESSAGE => "Category does not exist or is invalid",
];

// PERMISSIONS
const PERM_READ_PROFILE = 1; // 1
const PERM_WRITE_PROFILE = 1 << 1; // 2
const PERM_PROFILE = PERM_READ_PROFILE | PERM_WRITE_PROFILE;
const PERM_READ_PUZZLE = 1 << 2; // 4
const PERM_CREATE_PUZZLE = 1 << 3; // 8
const PERM_EDIT_PUZZLE = 1 << 4; // 16
const PERM_DELETE_PUZZLE = 1 << 5; // 32
const PERM_WRITE_PUZZLE = PERM_CREATE_PUZZLE | PERM_EDIT_PUZZLE | PERM_DELETE_PUZZLE;
const PERM_PUZZLE = PERM_READ_PUZZLE | PERM_WRITE_PUZZLE;
const PERM_READ_WISHLIST = 1 << 6; // 64
const PERM_CREATE_WISHLIST = 1 << 7; // 128
const PERM_EDIT_WISHLIST = 1 << 8; // 256
const PERM_DELETE_WISHLIST = 1 << 9; // 512
const PERM_WRITE_WISHLIST = PERM_CREATE_WISHLIST | PERM_EDIT_WISHLIST | PERM_DELETE_WISHLIST;
const PERM_WISHLIST = PERM_READ_WISHLIST | PERM_WRITE_WISHLIST;
const PERM_READ_USER_INVENTORY = 1 << 10; // 1024
const PERM_ADD_USER_INVENTORY = 1 << 11; // 2048
const PERM_EDIT_USER_INVENTORY = 1 << 12; // 4096
const PERM_REMOVE_USER_INVENTORY = 1 << 13; // 8192
const PERM_WRITE_USER_INVENTORY = PERM_ADD_USER_INVENTORY | PERM_EDIT_USER_INVENTORY | PERM_REMOVE_USER_INVENTORY;
const PERM_USER_INVENTORY = PERM_READ_USER_INVENTORY | PERM_WRITE_USER_INVENTORY;
const PERM_READ_MISC = 1 << 14; // 16384
const PERM_CREATE_MISC = 1 << 15; // 32768
const PERM_EDIT_MISC = 1 << 16; // 65536
const PERM_DELETE_MISC = 1 << 17; // 131072
const PERM_WRITE_MISC = PERM_CREATE_MISC | PERM_EDIT_MISC | PERM_DELETE_MISC;
const PERM_MISC = PERM_READ_MISC | PERM_WRITE_MISC;

const PERM_READ = PERM_READ_PROFILE | PERM_READ_PUZZLE | PERM_READ_WISHLIST | PERM_READ_USER_INVENTORY | PERM_READ_MISC;
const PERM_WRITE = PERM_PROFILE | PERM_PUZZLE | PERM_WISHLIST | PERM_USER_INVENTORY | PERM_MISC;

const COMPOUND_PERMS = [
    PERM_READ,
    PERM_WRITE,
    PERM_PROFILE,
    PERM_WRITE_PUZZLE,
    PERM_PUZZLE,
    PERM_WRITE_WISHLIST,
    PERM_WISHLIST,
    PERM_WRITE_USER_INVENTORY,
    PERM_USER_INVENTORY,
    PERM_WRITE_MISC,
    PERM_MISC
];
const PERM_LOOKUP = [
    PERM_READ => "read",
    PERM_WRITE => "write",
    PERM_READ_PROFILE => "read_profile",
    PERM_WRITE_PROFILE => "write_profile",
    PERM_PROFILE => "profile",
    PERM_READ_PUZZLE => "read_puzzle",
    PERM_CREATE_PUZZLE => "create_puzzle",
    PERM_EDIT_PUZZLE => "edit_puzzle",
    PERM_DELETE_PUZZLE => "delete_puzzle",
    PERM_WRITE_PUZZLE => "write_puzzle",
    PERM_PUZZLE => "puzzle",
    PERM_READ_WISHLIST => "read_wishlist",
    PERM_CREATE_WISHLIST => "create_wishlist",
    PERM_EDIT_WISHLIST => "edit_wishlist",
    PERM_DELETE_WISHLIST => "delete_wishlist",
    PERM_WRITE_WISHLIST => "write_wishlist",
    PERM_WISHLIST => "wishlist",
    PERM_READ_USER_INVENTORY => "read_user_inventory",
    PERM_ADD_USER_INVENTORY => "add_user_inventory",
    PERM_EDIT_USER_INVENTORY => "edit_user_inventory",
    PERM_REMOVE_USER_INVENTORY => "remove_user_inventory",
    PERM_WRITE_USER_INVENTORY => "write_user_inventory",
    PERM_USER_INVENTORY => "user_inventory",
    PERM_READ_MISC => "read_misc",
    PERM_CREATE_MISC => "create_misc",
    PERM_EDIT_MISC => "edit_misc",
    PERM_DELETE_MISC => "delete_misc",
    PERM_WRITE_MISC => "write_misc",
    PERM_MISC => "misc",
];
