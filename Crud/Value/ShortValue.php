<?php

namespace Tale\Crud\Value;


class ShortType extends IntValue
{

    const MIN = -32768;
    const MAX = 32767;
    const UNSIGNED_MIN = 0;
    const UNSIGNED_MAX = 65535;
}