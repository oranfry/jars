<?php
namespace jars\linetype;

class transferin extends \Linetype
{
    public function __construct()
    {
        $this->table = 'transfer';
        $this->label = 'Internal Transfer';
        $this->icon = 'arrowleftright';
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
                'fuse' => "'arrowleftright'",
                'derived' => true,
            ],
            (object) [
                'name' => 'date',
                'type' => 'date',
                'id' => true,
                'groupable' => true,
                'fuse' => 't.date',
            ],
            (object) [
                'name' => 'from',
                'type' => 'text',
                'fuse' => 't.fromjar',
            ],
            (object) [
                'name' => 'jar',
                'type' => 'text',
                'fuse' => 't.tojar',
                'label' => 'to',
            ],
            (object) [
                'name' => 'amount',
                'type' => 'number',
                'dp' => 2,
                'fuse' => 't.amount',
                'summary' => 'sum',
            ],
        ];
        $this->unfuse_fields = [
            't.date' => ':date',
            't.tojar' => ':jar',
            't.fromjar' => ':from',
            't.amount' => ':amount',
        ];
    }

    public function get_suggested_values()
    {
        $suggested_values = [];

        $suggested_values['from'] = get_values('transfer', 'fromjar');
        $suggested_values['jar'] = get_values('transfer', 'tojar');

        return $suggested_values;
    }

    public function validate($line)
    {
        $errors = [];

        if ($line->date == null) {
            $errors[] = 'no date';
        }

        if ($line->jar == null) {
            $errors[] = 'no jar';
        }

        if ($line->amount == null) {
            $errors[] = 'no amount';
        }

        if ($line->amount < 0) {
            $errors[] = 'amount is negative';
        }

        return $errors;
    }
}
