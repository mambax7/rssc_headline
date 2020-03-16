README FIRST
-----------------------

This module has same feature as XoopsHeadline module.

* The module overview *
This is based on XoopsHeadline,
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
this module does not delete link record in rssheadline module.
And then, this module can not show RSS feed for link record in rssheadline module.

Admin can correct this problem.
Admin add same link whitch was deleted in RSSC module.
And modify link ID of added link in in rssheadline module.

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
Admin delete link record with same "URL of the RDF/RSS file" in rssheadline module and RSSC module.
