<?php

declare(strict_types=1);

namespace Inane\Version;

enum VersionMatch: int {
    case LOWER = -1;
    case EQUAL = 0;
    case HIGHER = 1;
}
