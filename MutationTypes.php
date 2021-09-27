<?php

namespace Adilarry\GALib;

class MutationTypes
{
    /**
     * Specifies the bit flip mutation algorithm type
     *
     * @var string
     */
    public const BIT_FLIP = 'BIT_FLIP';

    /**
     * Specifies the random resetting mutation algorithm type
     *
     * @var string
     */
    public const RANDOM_RESETTING = 'RANDOM_RESETTING';

    /**
     * Specifies the swap mutation algorithm type
     *
     * @var string
     */
    public const SWAP_MUTATION = 'SWAP_MUTATION';

    /**
     * Specifies the scramble mutation algorithm type
     *
     * @var string
     */
    public const SCRAMBLE_MUTATION = 'SCRAMBLE_MUTATION';

    /**
     * Specifies the inversion mutation algorithm type
     *
     * @var string
     */
    public const INVERSION_MUTATION = 'INVERSION_MUTATION';
}