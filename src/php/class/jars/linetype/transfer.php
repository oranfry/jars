<?php
namespace jars\linetype;

class transfer extends \Linetype
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
                'fuse' => '{t}.date',
            ],
            (object) [
                'name' => 'from',
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
                'fuse' => '{t}.amount',
            ],
        ];
        $this->unfuse_fields = [
            '{t}.date' => ':{t}_date',
            '{t}.fromjar' => ':{t}_from',
            '{t}.tojar' => ':{t}_to',
            '{t}.amount' => ':{t}_amount',
        ];
    }

    public function get_suggested_values()
    {
        $suggested_values = [];

        $suggested_values['from'] = get_values('jar', 'jar');
        $suggested_values['to'] = get_values('jar', 'jar');

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

        if (!@$line->to) {
            $errors[] = 'no to jar';
        }

        if (!@$line->amount) {
            $errors[] = 'no amount';
        }

        return $errors;
    }
}
