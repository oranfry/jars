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
            '{t}.date' => (object) [
                'expression' => ':{t}_date',
                'type' => 'date',
            ],
            '{t}.jar' => (object) [
                'expression' => ':{t}_jar',
                'type' => 'varchar(40)',
            ],
            '{t}.account' => (object) [
                'expression' => ':{t}_account',
                'type' => 'varchar(40)',
            ],
            '{t}.description' => (object) [
                'expression' => ':{t}_description',
                'type' => 'varchar(255)',
            ],
            '{t}.amount' => (object) [
                'expression' => ':{t}_amount',
                'type' => 'decimal(18, 2)',
            ],
        ];
    }

    public function get_suggested_values($token)
    {
        $jars = get_values($token, 'jar', 'jar');
        sort($jars);

        $suggested_values = [];

        $suggested_values['jar'] = $jars;
        $suggested_values['account'] = get_values($token, 'transaction', 'account');

        return $suggested_values;
    }

    public function validate($line)
    {
        $errors = [];

        if (!@$line->date) {
            $errors[] = 'no date';
        }

        if (!@$line->jar) {
            $errors[] = 'no jar';
        }

        if (!@$line->amount) {
            $errors[] = 'no price';
        }

        return $errors;
    }
}
