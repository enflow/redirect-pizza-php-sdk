<?php

namespace RedirectPizza\PhpSdk\Enums;

enum AnalyticsDimension: string
{
    case Redirects = 'redirects';
    case Sources = 'sources';
    case Countries = 'countries';
    case Referers = 'referers';
    case RefererHosts = 'referer-hosts';
    case TrafficTypes = 'traffic-types';
    case Schemes = 'schemes';
    case DeviceTypes = 'device-types';
    case FullUrls = 'full-urls';
}
