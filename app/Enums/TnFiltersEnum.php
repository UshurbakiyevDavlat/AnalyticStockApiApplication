<?php

namespace App\Enums;

enum TnFiltersEnum: string
{
    /**
     * @const string FIELD title
     */
    case FIELD = 'filter[filters][0][field]';

    /**
     * @const string OPERATOR title
     */
    case OPERATOR = 'filter[filters][0][operator]';

    /**
     * @const string VALUE title
     */
    case VALUE = 'filter[filters][0][value]';

    /**
     * @const string FIELD_1 title
     */
    case FIELD_1 = 'filter[filters][1][field]';

    /**
     * @const string OPERATOR_1 title
     */
    case OPERATOR_1 = 'filter[filters][1][operator]';

    /**
     * @const string VALUE_1 title
     */
    case VALUE_1 = 'filter[filters][1][value]';

    /**
     * @const string TAKE title
     */
    case TAKE = 'take';

    /**
     * @const string SKIP title
     */
    case SKIP = 'skip';

    /**
     * @const string SHARES title
     */
    case SHARES = 'shares';

    /**
     * @const string OBLIGATIONS title
     */
    case OBLIGATIONS = 'obligations';
}
