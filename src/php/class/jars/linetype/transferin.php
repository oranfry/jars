<?php
namespace jars\linetype;

class transferin extends \Linetype
{
    public function __construct()
    {
        $this->table = 'transfer';
        $this->label = 'Internal Transfer';
        $this->icon = 'arrowright';
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
                'fuse' => "'arrowright'",
                'derived' => true,
            ],
            (object) [
                'name' => 'date',
                'type' => 'date',
                'id' => true,
                'groupable' => true,
                'fuse' => '{t}.date',
            ],
            (object) [
                'name' => 'from',
                'type' => 'text',
                'fuse' => '{t}.fromjar',
            ],
            (object) [
                'name' => 'jar',
                'type' => 'text',
                'fuse' => '{t}.tojar',
                'label' => 'to',
            ],
            (object) [
                'name' => 'amount',
                'type' => 'number',
                'dp' => 2,
                'fuse' => '{t}.amount',
                'summary' => 'sum',
            ],
        ];
        $this->unfuse_fields = [
            '{t}.date' => ':{t}_date',
            '{t}.tojar' => ':{t}_jar',
            '{t}.fromjar' => ':{t}_from',
            '{t}.amount' => ':{t}_amount',
        ];
    }

    public function get_suggested_values($token)
    {
        $suggested_values = [];

        $suggested_values['from'] = get_values($token, 'jar', 'jar');
        $suggested_values['jar'] = get_values($token, 'jar', 'jar');

        return $suggested_values;
    }

    public function validate($line)
    {
        $errors = [];

        if (!@$line->date) {
            $errors[] = 'no date';
        }

        if (!@$line->from) {
            $errors[] = 'no from jar';
        }

        if (!@$line->jar) {
            $errors[] = 'no to jar';
        }

        if (!(float)@$line->amount) {
            $errors[] = 'no amount';
        }

        if ($line->amount < 0) {
            $errors[] = 'amount is negative';
        }

        return $errors;
    }
}
