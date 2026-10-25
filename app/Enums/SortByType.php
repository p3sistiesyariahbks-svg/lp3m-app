<?php

namespace App\Enums;

enum SortByType: string
{
    case Latest = 'latest';
    case Oldest = 'oldest';
    case TitleAsc = 'title_asc';
    case TitleDesc = 'title_desc';
    case Popular = 'popular';
}
