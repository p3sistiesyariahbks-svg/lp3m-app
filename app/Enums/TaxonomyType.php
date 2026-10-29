<?php

namespace App\Enums;

enum TaxonomyType: string
{
    case News = 'news';
    case Documents = 'documents';
    case Gallery = 'gallery';
}
