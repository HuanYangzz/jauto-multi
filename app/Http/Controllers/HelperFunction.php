<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\SalesmanBranch;
use App\Models\PersonInCharge;
use App\Models\Vehicle;
use App\Models\VehicleVariant;
use App\Models\Vso;
use App\Models\VsoDetail;
use App\Models\VsoDetailAccessory;
use App\Models\VehicleAccessoryModel;
use App\Models\Accessory;
use App\Models\AccessorySupplier;
use App\Models\NonStockModel;
use App\Models\NonStock;
use App\Models\Fee;
use App\Models\FeeGroup;
use App\Models\SalesPromotion;
use App\Models\SalesPromotionItem;
use App\Models\Notification;
use App\Models\CommissionPackage;
use App\Models\CommissionPackageRule;
use App\Models\CommissionPackageUser;
use App\Models\CommissionRule;

use Auth;
use DB;
use DateTime;
use Carbon\Carbon;

class HelperFunction
{
    public static function convertContact($contact)
    {
        $str = "";
        if($contact!="")
        {
            $tmp = $contact;
            $is01 = $tmp[1]=="1";
            $str = "+6".substr($tmp,0,1)." ".($is01?substr($tmp,1,2):substr($tmp,1,1))."-".($is01?(substr($tmp,3,3)." ".substr($tmp,6)):(substr($tmp,2,3)." ".substr($tmp,5)));
        }
        
        return $str;
    }

    public static function convertContacts($list,$seperator)
    {
        $result = '';
        foreach($list as $tmp)
        {
            if($tmp!="")
            {
                $is01 = $tmp[1]=="1";
                $str = "+6".substr($tmp,0,1)." ".($is01?substr($tmp,1,2):substr($tmp,1,1))."-".($is01?(substr($tmp,3,3)." ".substr($tmp,6)):(substr($tmp,2,3)." ".substr($tmp,5)));
                $result.=($result!=""?$seperator:"").$str;
            }
        }
        return $result;
    }

    public static function search_array($list,$field,$value)
    {
        foreach($list as $item)
        {
            if($item->{$field}==$value)
            {
                return $item;
            }
        }
        return null;
    }
}