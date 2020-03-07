$Id: readme.txt,v 1.2 2011/12/29 20:06:57 ohwada Exp $

=================================================
Version: 1.20
Date:   2011-12-29
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This module has same feature as XoopsHeadline module.

* Changes *
1. Migrating to PHP 5.3
Deprecated features in PHP 5.3.x
http://www.php.net/manual/en/migration53.deprecated.php
(1) Assigning the return value of new by reference is now deprecated.

1. Migrating to MySQL 5.5
(1) TYPE=MyISAM -> ENGINE=MyISAM
(2) BLOB/TEXT can't have a default value


=================================================
Version: 1.15
Date:   2008-01-18
=================================================

This module has same feature as XoopsHeadline module.

* main change *
(1) added German
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=377&forum=5


=================================================
Version: 1.14
Date:   2007-08-01
=================================================

This module has same feature as XoopsHeadline module.

* main change *
(1) added Japanese UTF-8 files


=================================================
Version: 1.13
Date:   2007-06-09
Author: Kenichi OHWADA
=================================================

This module has same feature as XoopsHeadline module.

* main change *
(1) change some, according to the change of the RSSC module (v0.60).
(2) require happy_linux module v0.90.


=================================================
Version: 1.12
Date:   2007-05-20
=================================================

This module has same feature as XoopsHeadline module.

* main change *
(1) show pubdate if not exist lastbuilddate
(2) added Persian laguage files (translated by xoops persian)


=================================================
Version: 1.11
Date:   2006-09-10
=================================================

This module has same feature as XoopsHeadline module.

* main change *
(1) change some, according to the change of the RSSC module.
(2) support RSS which have guid field with non URL format.
(3) show error message, if cannot parse RSS feed, when add new link.


=================================================
Version: 1.10
Date:   2006-07-10
=================================================

This module has same feature as XoopsHeadline module.

* The module overview *
This is based on oopsHeadline,
and be changed to use the RSS feed function of RSSC module.
This is created as the sample of the cooperation feature of the RSSC module.

* difference to XoopsHeadline *
This changes is minimum for necessity.
The bad convenience in XoopsHeadline, this module is succeded as it is.
By using the cooperation feature of the RSSC module,
the following point is improved.

(1) support RSS Auto Discovery
If the web site supports RSS Auto Discovery,
when admin will register new web site and not fill "URL of RDF/RSS file" and "RSS encoding",
this module wil detect and set twe fields automatically.
If this module does not detected, this shows warning message.
Also, this module check to exist record with same "URL of RDF/RSS file" in RSSC module.
If this module find same record,
this use the existence record in RSSC module.

(2) support "allow_url_fopen = off"


* requirement *
happy_linux module and the RSS center module are necessary.


* some problems *
The discordance with twe modules is ocuured, 
becuase of cooperation feature with twe modules.

(1) when deleted a link record in RSSC module.
Even if admin delete a link record in RSSC module,
this module does not delete link record in rssc_headline module.
And then, this module can not show RSS feed for link record in rssc_headline module.

Admin can correct this problem.
Admin add same link whitch was deleted in RSSC module.
And modify link ID of added link in in rssc_headline module.

(2) there are twe or more link record with same "URL of the RDF/RSS file" in RSSC module.
When admin add link with mistake "URL of the RDF/RSS file" in rssc_hedline module,
and then this module add same mistake link in RSSC module.
Admin modify "URL of the RDF/RSS file",
and then this module modify link.
Therefore there are twe or more link record with same "URL of the RDF/RSS file" in RSSC module.

When link A cache id updated, RSSC module add RSS feed which belongs to link A.
And when link B cache id updated, RSSC module add RSS feed which belongs to link B.
RSSC module has the mechanism that not add same RSS feed.
Therefore, some RSS feed 1,3,5 belongs to link A,
and other RSS feed 2,4,6 belong to the link B.

For desirable feature,
when admin select link A or the link B.
this module will show all RSS feed 1,2,3,4,5,6
In present,
when admin select link A,
this module can show RSS feed 1,3,5 which belongs to link A.

Admin can correct this problem.
Admin delete link record with same "URL of the RDF/RSS file" in rssc_headline module and RSSC module.

