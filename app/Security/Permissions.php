<?php


namespace App\Security;


class Permissions
{
    //User
    public const READ_USER = "read user";
    public const EDIT_USER = "edit user";
    public const DELETE_USER = "delete user";

    //Products
    public const CREATE_PRODUCT = "create product";
    public const EDIT_PRODUCT = "edit product";
    public const DELETE_PRODUCT = "delete product";

    //Categories
    public const READ_CATEGORY = "read category";
    public const CREATE_CATEGORY = "create category";
    public const EDIT_CATEGORY = "edit category";
    public const DELETE_CATEGORY = "delete category";

    //Sales
    public const READ_SALES = "read sales";
}
