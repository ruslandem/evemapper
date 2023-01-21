<?php

declare(strict_types=1);

namespace App\Services;

enum CosmicSignatureUpsertStatus: int
{
    case Unchanged = 0;
    case Updated = 1;
    case Created = 2;
}
