<?php
namespace jars\linetype;

class transaction extends \Linetype
{
    public function __construct()
    {
        $this->table = 'transaction';
        $this->label = 'Transaction';
        $this->icon = 'dollar';
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
                'fuse' => "'dollar'",
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
                'suggest' => true,
                'groupable' => true,
                'fuse' => '{t}.jar',
            ],
            (object) [
                'name' => 'account',
                'type' => 'text',
                'suggest' => true,
                'fuse' => '{t}.account',
            ],
            (object) [
                'name' => 'description',
                'type' => 'text',
                'fuse' => '{t}.description',
            ],
            (object) [
                'name' => 'amount',
                'type' => 'number',
                'dp' => 2,
                'summary' => 'sum',
                'fuse' => '{t}.amount',
            ],
            (object) [
                'name' => 'broken',
                'type' => 'text',
                'derived' => true,
                'fuse' => "if ({t}.jar is null or {t}.jar = '', 'broken', '')",
            ],
        ];
        $this->unfuse_fields = [
            '{t}.date' => ':{t}_date',
            '{t}.jar' => ':{t}_jar',
            '{t}.account' => ':{t}_account',
            '{t}.description' => ':{t}_description',
            '{t}.amount' => ':{t}_amount',
        ];
    }

    public function get_suggested_values()
    {
        $jars = array_values(array_unique(array_merge(
            get_values('transaction', 'jar'),
            get_values('transfer', 'fromjar'),
            get_values('transfer', 'tojar')
        )));

        sort($jars);

        $suggested_values = [];

        $suggested_values['jar'] = $jars;
        $suggested_values['account'] = get_values('transaction', 'account');

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
            $errors[] = 'no price';
        }

        return $errors;
    }
}
