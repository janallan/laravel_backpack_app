<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NEW_DEAL()
 * @method static static MISSING_INFO()
 * @method static static DEAL_WON()
 * @method static static DEAL_LOST()
 */
final class SalesStage extends Enum
{
    const NEW_DEAL =   0;
    const MISSING_INFO =   1;
    const DEAL_WON = 2;
    const DEAL_LOST = 3;
}
