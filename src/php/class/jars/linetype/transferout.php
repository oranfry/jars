<?php
namespace jars\linetype;

class transferout extends \Linetype
{
    public function __construct()
    {
        $this->table = 'transfer';
        $this->label = 'Internal Transfer';
        $this->icon = 'arrowleft';
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
                'fuse' => "'arrowleft'",
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
                'name' => 'jar',
                'type' => 'text',
                'fuse' => '{t}.fromjar',
            ],
            (object) [
                'name' => 'to',
                'type' => 'text',
                'fuse' => '{t}.tojar',
            ],
            (object) [
                'name' => 'amount',
                'type' => 'number',
                'dp' => 2,
                'fuse' => '-{t}.amount',
                'summary' => 'sum',
            ],
        ];
        $this->unfuse_fields = [
            '{t}.date' => ':{t}_date',
            '{t}.tojar' => ':{t}_to',
            '{t}.fromjar' => ':{t}_jar',
            '{t}.amount' => '0 - :{t}_amount',
        ];
    }

    public function get_suggested_values($token)
    {
        $suggested_values = [];

        $suggested_values['to'] = get_values($token, 'jar', 'jar');
        $suggested_values['jar'] = get_values($token, 'jar', 'jar');

        return $suggested_values;
    }

    public function validate($line)
    {
        $errors = [];

        if (!@$line->date) {
            $errors[] = 'no date';
        }

        if (!@$line->jar) {
            $errors[] = 'no to jar';
        }


        if (!@$line->to) {
            $errors[] = 'no from jar';
        }

        if (!@$line->amount) {
            $errors[] = 'no amount';
        }

        if ($line->amount > 0) {
            $errors[] = 'amount is positive';
        }

        return $errors;
    }
}
