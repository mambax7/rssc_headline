<?php
// $Id: admin.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2006-09-01 K.OHWADA
// use RSSC lang pack

// 2006-07-02 K.OHWADA

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: admin.php,v 1.2 2005/03/18 12:52:49 onokazu Exp
//=========================================================

//%%%%%%        Admin Module Name  Headlines         %%%%%
define('_AM_RSSHEADLINE_DBUPDATED', 'پایگاه داده ها با موفقیت به روز شد!');
define('_AM_RSSHEADLINE_HEADLINES', 'پیکر بندی تیتر های خبری');
define('_AM_RSSHEADLINE_HLMAIN', 'صفحه ی اصلی تیتر های خبری');
define('_AM_RSSHEADLINE_SITENAME', 'نام سایت');
define('_AM_RSSHEADLINE_URL', 'آدرس');
define('_AM_RSSHEADLINE_ORDER', 'وزن چینش');
define('_AM_RSSHEADLINE_ENCODING', 'اینکودینگ RSS');
define('_AM_RSSHEADLINE_CACHETIME', 'زمان کش');
define('_AM_RSSHEADLINE_MAINSETT', 'تنظیمات صفحه ی اصلی');
define('_AM_RSSHEADLINE_BLOCKSETT', 'تنظیمات بلاک ها');
define('_AM_RSSHEADLINE_DISPLAY', 'نمایش در صفحه ی اصلی');
define('_AM_RSSHEADLINE_DISPIMG', 'نمایش تصویر');
define('_AM_RSSHEADLINE_DISPFULL', 'نمایش در اندازه ی اصلی');
define('_AM_RSSHEADLINE_DISPMAX', 'بیشترین تعداد نمایش داده شده');
define('_AM_RSSHEADLINE_ASBLOCK', 'نمایش در بلاک');
define('_AM_RSSHEADLINE_ADDHEADL', 'اضافه کردن یه تیتر خبری');
define('_AM_RSSHEADLINE_URLEDFXML', 'آدرس فایل RDF/RSS');
define('_AM_RSSHEADLINE_EDITHEADL', 'ویرایش تیتر خبری');
define('_AM_RSSHEADLINE_WANTDEL', 'آیا شما میخواید این تیتر خبری را از %s پاک کنید؟');
define('_AM_RSSHEADLINE_INVALIDID', 'ID را وارد کنید');
define('_AM_RSSHEADLINE_OBJECTNG', 'شیع انتخواب شما موجود نیست');
define('_AM_RSSHEADLINE_FAILUPDATE', 'خطا در ذخیره کردن اطلاعات تیتر خبری %s در پایگاه داده ها');
define('_AM_RSSHEADLINE_FAILDELETE', 'خطا در پاک کردن اطلاعات تیتر خبری %s از پایگاه داده ها');

// 2006-07-02 K.OHWADA
define('_AM_RSSHEADLINE_INDEX_DESC', 'Discover <b>URL of RDF/RSS fileL</b> automatically and detect <b>RSS Encoding</b> automatically, <br>when you dont fill, <br>if web site support "RSS Auto Discovery"');
//define('_AM_RSSHEADLINE_RSSC_LINK_RSS_EXIST','Already exists this "RDF/RSS URL"');
//define('_AM_RSSHEADLINE_RSSC_LINK_RSS_EXIST_MORE','There are twe or more links which have same "RDF/RSS/ATOM URL"');
//define('_AM_RSSHEADLINE_RSSC_LINK_LID_EXIST_NOT','هیچ لینکی در این لینک موجود نیست');
//define('_AM_RSSHEADLINE_RSSC_AUTO_FAILD','یک خطا در RSS پیدا شده');
//define('_AM_RSSHEADLINE_RSSC_LID','لینک ID از RSS center ');
//define('_AM_RSSHEADLINE_RSSC_LID_UPDATE','به روز کردن لینک ID');
//define('_AM_RSSHEADLINE_RSSC_GOTO_LINK','برو به صفحه ی مدیریت ماژول RSS center');
